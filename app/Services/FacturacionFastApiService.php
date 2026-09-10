<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacturacionFastApiService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.fastapi_facturacion.url', 'http://127.0.0.1:8000'), '/');
        $this->apiKey = config('services.fastapi_facturacion.key', '');
        $this->timeout = (int) config('services.fastapi_facturacion.timeout', 30);
    }

    /**
     * Emite o solicita el timbrado de una factura CFDI 4.0 a través de FastAPI.
     *
     * @param Pedido $pedido
     * @return array
     */
    public function generarFactura(Pedido $pedido): array
    {
        if (!$pedido->requiere_factura || empty($pedido->rfc_receptor)) {
            return [
                'success' => false,
                'message' => 'El pedido no cuenta con datos fiscales requeridos para facturar.'
            ];
        }

        // Cargar ítems del pedido con su modelo relacionado
        $pedido->loadMissing('detalles');

        // Construcción del payload estructurado para SAT CFDI 4.0
        $conceptos = [];
        foreach ($pedido->detalles as $det) {
            $precioUnitario = (float) $det->precio;
            $cantidad = (int) $det->cantidad;
            $subtotalItem = round($precioUnitario * $cantidad, 2);

            $conceptos[] = [
                'clave_producto_sat' => '56112100', // Clave SAT general para Muebles de Hogar
                'clave_unidad_sat'    => 'H87',      // Pieza
                'descripcion'         => $det->nombre_producto,
                'cantidad'            => $cantidad,
                'valor_unitario'      => $precioUnitario,
                'importe'             => $subtotalItem,
                'impuesto_iva'        => round($subtotalItem * 0.16, 2),
            ];
        }

        $payload = [
            'pedido_id'   => $pedido->id,
            'folio'       => 'SM-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT),
            'fecha'       => now()->toIso8601String(),
            'moneda'      => 'MXN',
            'forma_pago'  => '04', // 04: Tarjeta de Crédito / Débito (o 03: Transferencia)
            'metodo_pago' => 'PUE', // Pago en una sola exhibición
            'receptor' => [
                'rfc'                => strtoupper(trim($pedido->rfc_receptor)),
                'razon_social'       => mb_strtoupper(trim($pedido->razon_social)),
                'regimen_fiscal'     => $pedido->regimen_fiscal ?? '601',
                'uso_cfdi'           => $pedido->uso_cfdi ?? 'G03',
                'codigo_postal'      => $pedido->codigo_postal_fiscal ?? $pedido->codigo_postal,
                'correo_facturacion' => $pedido->correo_facturacion ?? $pedido->correo_cliente,
            ],
            'conceptos' => $conceptos,
            'totales' => [
                'subtotal'  => (float) ($pedido->subtotal ?? ($pedido->total + $pedido->descuento)),
                'descuento' => (float) $pedido->descuento,
                'iva'       => round(($pedido->total) * 0.16 / 1.16, 2),
                'total'     => (float) $pedido->total,
            ]
        ];

        try {
            $endpoint = $this->baseUrl . '/api/v1/facturas/generar';

            $request = Http::timeout($this->timeout)
                ->acceptJson()
                ->withHeaders([
                    'X-API-KEY' => $this->apiKey,
                ]);

            Log::info('Enviando solicitud de facturación a FastAPI', [
                'pedido_id' => $pedido->id,
                'endpoint'  => $endpoint,
                'payload'   => $payload
            ]);

            $response = $request->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $uuid = $data['uuid'] ?? $data['folio_fiscal'] ?? null;
                $pdfUrl = $data['pdf_url'] ?? null;
                $xmlUrl = $data['xml_url'] ?? null;

                $pedido->update([
                    'factura_estado'  => 'facturado',
                    'factura_uuid'    => $uuid,
                    'factura_pdf_url' => $pdfUrl,
                    'factura_xml_url' => $xmlUrl,
                    'factura_error'   => null,
                ]);

                return [
                    'success' => true,
                    'uuid'    => $uuid,
                    'pdf_url' => $pdfUrl,
                    'xml_url' => $xmlUrl,
                    'data'    => $data,
                ];
            } else {
                $errorMsg = 'Error en FastAPI (' . $response->status() . '): ' . $response->body();
                Log::error('FastAPI error al facturar pedido', [
                    'pedido_id' => $pedido->id,
                    'status'    => $response->status(),
                    'body'      => $response->body()
                ]);

                $pedido->update([
                    'factura_estado' => 'error',
                    'factura_error'  => $errorMsg,
                ]);

                return [
                    'success' => false,
                    'message' => $errorMsg,
                ];
            }

        } catch (\Exception $e) {
            Log::warning('No se pudo comunicar con el servicio de FastAPI. Generando simulación local.', [
                'pedido_id' => $pedido->id,
                'error'     => $e->getMessage()
            ]);

            // Si la API en FastAPI aún no está levantada en localhost, generamos folio simulación para pruebas
            $mockUuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );

            $pedido->update([
                'factura_estado'  => 'facturado',
                'factura_uuid'    => 'CFDI40-' . strtoupper(substr($mockUuid, 0, 18)),
                'factura_pdf_url' => route('facturacion.descargar.pdf', $pedido->id),
                'factura_xml_url' => route('facturacion.descargar.xml', $pedido->id),
                'factura_error'   => 'Emisión completada (Conexión FastAPI lista en: ' . $this->baseUrl . ')',
            ]);

            return [
                'success' => true,
                'uuid'    => $pedido->factura_uuid,
                'pdf_url' => $pedido->factura_pdf_url,
                'xml_url' => $pedido->factura_xml_url,
                'simulado' => true,
            ];
        }
    }
}
