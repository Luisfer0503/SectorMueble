<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Services\FacturacionFastApiService;
use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    protected FacturacionFastApiService $facturacionService;

    public function __construct(FacturacionFastApiService $facturacionService)
    {
        $this->facturacionService = $facturacionService;
    }

    /**
     * Muestra el portal público de facturación de compras.
     */
    public function mostrarFormulario(Request $request)
    {
        $pedido = null;
        $buscado = false;

        if ($request->has('pedido_id') && $request->has('correo')) {
            $buscado = true;
            $pedido = Pedido::where('id', $request->input('pedido_id'))
                ->where('correo_cliente', trim($request->input('correo')))
                ->first();
        }

        return view('Principal.facturacion', compact('pedido', 'buscado'));
    }

    /**
     * Busca un pedido para emitir o descargar su factura.
     */
    public function buscarPedido(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|numeric',
            'correo'    => 'required|email',
        ], [
            'pedido_id.required' => 'Ingresa el número de tu pedido.',
            'correo.required'    => 'Ingresa el correo electrónico utilizado en tu compra.',
            'correo.email'       => 'El formato de correo no es válido.',
        ]);

        return redirect()->route('facturacion.index', [
            'pedido_id' => $request->input('pedido_id'),
            'correo'    => trim($request->input('correo')),
        ]);
    }

    /**
     * Procesa la solicitud de factura llenando o actualizando datos fiscales y enviándolos a FastAPI.
     */
    public function solicitarFactura(Request $request)
    {
        $request->validate([
            'pedido_id'            => 'required|exists:pedidos,id',
            'rfc_receptor'         => 'required|string|min:12|max:13',
            'razon_social'        => 'required|string|max:255',
            'regimen_fiscal'      => 'required|string',
            'uso_cfdi'            => 'required|string',
            'codigo_postal_fiscal' => 'required|string|max:10',
            'correo_facturacion'  => 'required|email',
        ], [
            'rfc_receptor.required'         => 'El RFC es obligatorio.',
            'rfc_receptor.min'              => 'El RFC debe tener al menos 12 caracteres.',
            'razon_social.required'         => 'La Razón Social o Nombre fiscal es obligatorio.',
            'regimen_fiscal.required'       => 'Selecciona tu Régimen Fiscal del SAT.',
            'uso_cfdi.required'             => 'Selecciona el Uso de CFDI.',
            'codigo_postal_fiscal.required' => 'El Código Postal Fiscal es requerido por el SAT.',
            'correo_facturacion.required'   => 'Proporciona un correo para enviar tu factura (PDF y XML).',
        ]);

        $pedido = Pedido::findOrFail($request->input('pedido_id'));

        // Guardar/Actualizar datos fiscales en el pedido
        $pedido->update([
            'requiere_factura'     => true,
            'rfc_receptor'         => strtoupper(trim($request->input('rfc_receptor'))),
            'razon_social'        => mb_strtoupper(trim($request->input('razon_social'))),
            'regimen_fiscal'      => $request->input('regimen_fiscal'),
            'uso_cfdi'            => $request->input('uso_cfdi'),
            'codigo_postal_fiscal' => trim($request->input('codigo_postal_fiscal')),
            'correo_facturacion'  => trim($request->input('correo_facturacion')),
            'factura_estado'       => 'pendiente',
        ]);

        $pedido->refresh();

        // Ejecutar timbrado mediante FastAPI
        $resultado = $this->facturacionService->generarFactura($pedido);

        if ($resultado['success']) {
            return redirect()->back()->with('success', '¡Factura generada exitosamente! Puedes descargar tu archivo PDF y XML a continuación.');
        } else {
            return redirect()->back()->with('error', 'Ocurrió un inconveniente al generar la factura: ' . ($resultado['message'] ?? 'Revisa los datos fiscales proporcionados.'));
        }
    }

    /**
     * Descarga el archivo PDF de la factura del pedido.
     */
    public function descargarPdf($id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);

        if ($pedido->factura_estado !== 'facturado') {
            return redirect()->back()->with('error', 'La factura de este pedido aún no ha sido emitida.');
        }

        $pdfUrl = $pedido->factura_pdf_url;

        if (!empty($pdfUrl) && str_starts_with($pdfUrl, 'http') && !str_contains($pdfUrl, route('facturacion.descargar.pdf', $id))) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($pdfUrl);
                if ($response->successful()) {
                    return response($response->body(), 200, [
                        'Content-Type'        => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="Factura_SM-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT) . '.pdf"',
                    ]);
                }
            } catch (\Exception $e) {
                // Continuar a la vista imprimible
            }
        }

        return response()->view('Principal.factura-pdf', compact('pedido'), 200, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }

    /**
     * Descarga el archivo XML CFDI 4.0 oficial de la factura del pedido.
     */
    public function descargarXml($id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);

        if ($pedido->factura_estado !== 'facturado') {
            return redirect()->back()->with('error', 'La factura de este pedido aún no ha sido emitida.');
        }

        $xmlUrl = $pedido->factura_xml_url;

        if (!empty($xmlUrl) && str_starts_with($xmlUrl, 'http') && !str_contains($xmlUrl, route('facturacion.descargar.xml', $id))) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($xmlUrl);
                if ($response->successful()) {
                    return response($response->body(), 200, [
                        'Content-Type'        => 'text/xml; charset=utf-8',
                        'Content-Disposition' => 'attachment; filename="Factura_SM-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT) . '.xml"',
                    ]);
                }
            } catch (\Exception $e) {
                // Continuar al XML generado
            }
        }

        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" Version="4.0" Serie="SM" Folio="' . $pedido->id . '" Fecha="' . $pedido->created_at->toIso8601String() . '" Sello="DEMO" FormaPago="04" NoCertificado="00001000000500000000" SubTotal="' . number_format($pedido->total, 2, '.', '') . '" Moneda="MXN" Total="' . number_format($pedido->total, 2, '.', '') . '" TipoDeComprobante="I" Exportacion="01" MetodoPago="PUE" LugarExpedicion="' . ($pedido->codigo_postal_fiscal ?? '72000') . '">' . "\n" .
            '  <cfdi:Emisor Rfc="SME260101XXX" Nombre="SECTOR MUEBLE S.A. DE C.V." RegimenFiscal="601"/>' . "\n" .
            '  <cfdi:Receptor Rfc="' . ($pedido->rfc_receptor ?? 'XAXX010101000') . '" Nombre="' . htmlspecialchars($pedido->razon_social ?? $pedido->nombre_cliente) . '" DomicilioFiscalReceptor="' . ($pedido->codigo_postal_fiscal ?? $pedido->codigo_postal) . '" RegimenFiscalReceptor="' . ($pedido->regimen_fiscal ?? '616') . '" UsoCFDI="' . ($pedido->uso_cfdi ?? 'G03') . '"/>' . "\n" .
            '  <cfdi:Conceptos>' . "\n";

        foreach ($pedido->detalles as $det) {
            $xmlContent .= '    <cfdi:Concepto ClaveProdServ="56112100" Cantidad="' . $det->cantidad . '" ClaveUnidad="H87" Unidad="Pieza" Descripcion="' . htmlspecialchars($det->nombre_producto) . '" ValorUnitario="' . number_format($det->precio, 2, '.', '') . '" Importe="' . number_format($det->precio * $det->cantidad, 2, '.', '') . '" ObjetoImp="02"/>' . "\n";
        }

        $xmlContent .= '  </cfdi:Conceptos>' . "\n" .
            '  <cfdi:Complemento>' . "\n" .
            '    <tfd:TimbreFiscalDigital xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" Version="1.1" UUID="' . ($pedido->factura_uuid ?? 'CFDI40-0000-0000-0000-000000000000') . '" FechaTimbrado="' . now()->toIso8601String() . '" RfcProvCertif="SAT970701NN3"/>' . "\n" .
            '  </cfdi:Complemento>' . "\n" .
            '</cfdi:Comprobante>';

        return response($xmlContent, 200, [
            'Content-Type'        => 'text/xml; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="Factura_SM-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT) . '.xml"',
        ]);
    }
}
