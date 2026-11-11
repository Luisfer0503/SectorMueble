<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'usuario_id',
        'nombre_cliente',
        'correo_cliente',
        'telefono_cliente',
        'direccion_envio',
        'ciudad',
        'codigo_postal',
        'total',
        'tipo_pago',
        'cupon_codigo',
        'descuento',
        'estado',
        'requiere_factura',
        'rfc_receptor',
        'razon_social',
        'regimen_fiscal',
        'uso_cfdi',
        'codigo_postal_fiscal',
        'correo_facturacion',
        'factura_estado',
        'factura_uuid',
        'factura_pdf_url',
        'factura_xml_url',
        'factura_error',
        'stripe_session_id',
        'checkout_fingerprint',
        'stripe_currency',
        'stripe_paid_at',
        'stock_reservado',
    ];

    protected $casts = [
        'requiere_factura' => 'boolean',
        'stock_reservado'  => 'boolean',
        'stripe_paid_at'   => 'datetime',
    ];

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Libera el stock reservado de este pedido de forma atómica e idempotente,
     * restaurando las existencias y actualizando el flag 'agotado' de cada variante.
     */
    public function liberarReservaStock(string $nuevoEstado = 'cancelado'): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($nuevoEstado) {
            $locked = self::with('detalles')->whereKey($this->id)->lockForUpdate()->first();
            if (!$locked || !$locked->stock_reservado || $locked->stripe_paid_at) {
                return;
            }

            foreach ($locked->detalles as $linea) {
                $detalle = null;
                if (!empty($linea->producto_detalle_id)) {
                    $detalle = ProductoDetalle::whereKey($linea->producto_detalle_id)->lockForUpdate()->first();
                }
                if (!$detalle && !empty($linea->producto_id)) {
                    $detalle = ProductoDetalle::where('producto_id', $linea->producto_id)
                        ->where('activo', true)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->first();
                }

                if ($detalle) {
                    $nuevoStock = (int) $detalle->stock + (int) $linea->cantidad;
                    $detalle->update([
                        'stock'   => $nuevoStock,
                        'agotado' => $nuevoStock <= 0,
                    ]);
                }
            }

            $locked->forceFill([
                'stock_reservado' => false,
                'estado'          => $nuevoEstado,
            ])->save();

            $this->stock_reservado = false;
            $this->estado = $nuevoEstado;
        });
    }

    /**
     * Libera automáticamente las reservas de stock de pedidos pendientes cuya sesión
     * de checkout ya superó el tiempo límite (respaldo si el webhook no llegó).
     */
    public static function liberarReservasVencidas(int $minutosMaximos = 36): void
    {
        $vencidos = self::with('detalles')
            ->where('stock_reservado', true)
            ->whereNull('stripe_paid_at')
            ->where('estado', 'pendiente')
            ->where('created_at', '<=', now()->subMinutes($minutosMaximos))
            ->get();

        if ($vencidos->isEmpty()) {
            return;
        }

        $stripeSecret = config('services.stripe.secret');
        $stripe = !empty($stripeSecret) ? new \Stripe\StripeClient($stripeSecret) : null;

        foreach ($vencidos as $pedido) {
            try {
                if ($stripe && !empty($pedido->stripe_session_id)) {
                    $session = $stripe->checkout->sessions->retrieve($pedido->stripe_session_id);
                    if (($session->payment_status ?? '') === 'paid') {
                        $pedido->forceFill([
                            'estado'          => 'procesando',
                            'stripe_paid_at'  => now(),
                            'stock_reservado' => false,
                        ])->save();
                        continue;
                    }
                    if (($session->status ?? '') === 'open') {
                        try {
                            $stripe->checkout->sessions->expire($pedido->stripe_session_id);
                        } catch (\Exception $e) {
                            // Ignorar si ya expiró
                        }
                    }
                }
                $pedido->liberarReservaStock('cancelado');
            } catch (\Exception $e) {
                if ($pedido->created_at && $pedido->created_at->lte(now()->subMinutes(45))) {
                    $pedido->liberarReservaStock('cancelado');
                }
            }
        }
    }

    /**
     * Devuelve la fecha de creación ajustada a la zona horaria de México (America/Mexico_City).
     * Si el registro fue guardado previamente en UTC (+6h), lo convierte automáticamente.
     */
    public function getCreatedAtMexicoAttribute(): \Carbon\Carbon
    {
        $dt = $this->created_at ? $this->created_at->copy() : now('America/Mexico_City');
        $ahoraMx = now('America/Mexico_City');

        // Si la fecha guardada en BD está adelantada respecto a la hora actual de México (guardada en UTC), restar 6 horas
        if ($dt->gt($ahoraMx->copy()->addMinutes(5))) {
            return $dt->subHours(6);
        }

        return $dt->setTimezone('America/Mexico_City');
    }

    /**
     * Devuelve la fecha de actualización ajustada a la zona horaria de México (America/Mexico_City).
     */
    public function getUpdatedAtMexicoAttribute(): \Carbon\Carbon
    {
        $dt = $this->updated_at ? $this->updated_at->copy() : $this->created_at_mexico;
        $ahoraMx = now('America/Mexico_City');

        if ($dt->gt($ahoraMx->copy()->addMinutes(5))) {
            return $dt->subHours(6);
        }

        return $dt->setTimezone('America/Mexico_City');
    }

    /**
     * Extrae el ID de pedido asociado a una sesión de Stripe Checkout.
     */
    public static function extraerPedidoIdDeSesionStripe($session): ?int
    {
        if (!empty($session->metadata->pedido_id) && is_numeric($session->metadata->pedido_id)) {
            return (int) $session->metadata->pedido_id;
        }
        if (!empty($session->client_reference_id) && is_numeric($session->client_reference_id)) {
            return (int) $session->client_reference_id;
        }
        if (!empty($session->success_url) && preg_match('/[?&]pedido_id=(\d+)/', (string) $session->success_url, $m)) {
            return (int) $m[1];
        }
        if (!empty($session->cancel_url) && preg_match('/[?&]pedido_id=(\d+)/', (string) $session->cancel_url, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    /**
     * Traduce códigos de rechazo bancario de Stripe a explicaciones claras en español.
     */
    public static function traducirCodigoRechazoStripe(?string $declineCode, ?string $errorCode, ?string $rawMsg = null): string
    {
        $code = strtolower((string) ($declineCode ?: $errorCode));
        $mapa = [
            'insufficient_funds'                   => 'Fondos insuficientes en la tarjeta del cliente.',
            'do_not_honor'                         => 'El banco emisor rechazó el cargo (Do Not Honor / bloqueo preventivo del banco).',
            'generic_decline'                      => 'Tarjeta declinada por el banco emisor sin motivo específico.',
            'card_declined'                        => 'La tarjeta fue declinada por el banco emisor.',
            'expired_card'                         => 'La tarjeta ingresada se encuentra vencida.',
            'incorrect_cvc'                        => 'El código de seguridad (CVV/CVC) ingresado fue incorrecto.',
            'invalid_cvc'                          => 'El código de seguridad (CVV/CVC) es inválido.',
            'incorrect_number'                     => 'El número de tarjeta ingresado es incorrecto.',
            'invalid_number'                       => 'El número de tarjeta ingresado no es válido.',
            'invalid_expiry_month'                 => 'El mes de vencimiento de la tarjeta es inválido.',
            'invalid_expiry_year'                  => 'El año de vencimiento de la tarjeta es inválido.',
            'authentication_required'              => 'El banco solicitó autenticación 3D Secure y no fue completada.',
            'three_d_secure_authentication_failed' => 'Falló la verificación de seguridad 3D Secure con el banco del cliente.',
            'processing_error'                     => 'Ocurrió un error temporal al procesar la tarjeta en la red bancaria.',
            'try_again_later'                      => 'El banco emisor solicitó intentar el cargo más tarde.',
            'Not_permitted'                        => 'Operación o compras en línea no permitidas en esta tarjeta.',
            'transaction_not_allowed'              => 'El banco del cliente no permite este tipo de transacción en línea.',
            'card_velocity_exceeded'               => 'Se excedió el límite de intentos o monto permitido por la tarjeta.',
            'withdrawal_count_limit_exceeded'      => 'El cliente superó el límite de compras/retiros de su tarjeta.',
            'lost_card'                            => 'Tarjeta reportada como extraviada ante el banco.',
            'stolen_card'                          => 'Tarjeta reportada como robada ante el banco.',
            'pickup_card'                          => 'Tarjeta restringida por el banco emisor.',
            'restricted_card'                      => 'Tarjeta con restricciones activas del banco emisor.',
            'fraudulent'                           => 'Transacción bloqueada por sospecha de fraude (banco o Stripe Radar).',
            'highest_risk_level'                   => 'Bloqueado automáticamente por el filtro antifraude de Stripe Radar.',
            'currency_not_supported'               => 'La tarjeta no soporta cobros en pesos mexicanos (MXN).',
            'call_issuer'                          => 'El banco requiere que el titular se comunique para autorizar el monto.',
        ];

        if (isset($mapa[$code])) {
            return $mapa[$code];
        }

        if (!empty($rawMsg)) {
            return $rawMsg;
        }

        return 'El cargo no pudo ser autorizado por el banco emisor.';
    }

    public function obtenerDiagnosticoStripe(): array
    {
        $tp = (string) ($this->tipo_pago ?? 'contado');
        $esMsi = str_starts_with($tp, 'msi_');
        $plazoMsi = $esMsi ? (int) str_replace('msi_', '', $tp) : 0;
        $modalidadTexto = ($esMsi && $plazoMsi > 0)
            ? "{$plazoMsi} Meses Sin Intereses (Stripe)"
            : 'Pago de Contado (Stripe)';

        $diagnostico = [
            'encontrado'           => false,
            'pagado_real'          => false,
            'monto_pedido'         => (float) $this->total,
            'monto_cobrado'        => 0.00,
            'moneda'               => 'MXN',
            'modalidad_texto'      => $modalidadTexto,
            'session_id'           => null,
            'payment_intent_id'    => null,
            'entorno'              => 'Stripe',
            'session_status_raw'   => null,
            'session_status_label' => 'Sin sesión registrada',
            'payment_status_raw'   => 'unpaid',
            'payment_status_label' => 'Sin cobro ($0.00 MXN)',
            'intentos_count'       => 0,
            'tarjeta_info'         => 'No llegó a ingresar datos de tarjeta',
            'codigo_rechazo'       => null,
            'motivo_categoria'     => 'sin_cobro',
            'motivo_titulo'        => 'Pedido Cancelado / Sin Cobro en Stripe ($0.00 MXN)',
            'motivo_explicacion'   => 'No se completó ningún pago en Stripe para este pedido. El monto cobrado en Stripe es de $0.00 MXN.',
            'fecha_sesion'         => $this->created_at_mexico->format('d/m/Y h:i:s A'),
            'historial_intentos'   => [],
        ];

        $stripeSecret = config('services.stripe.secret') ?: env('STRIPE_SECRET');
        if (empty($stripeSecret)) {
            return $diagnostico;
        }

        try {
            $stripe = new \Stripe\StripeClient($stripeSecret);
            if ($this->stripe_session_id) {
                $sesionesPedido = [$stripe->checkout->sessions->retrieve($this->stripe_session_id, [
                    'expand' => ['payment_intent'],
                ])];
            } else {
                $sesionesPedido = [];
                $sessions = $stripe->checkout->sessions->all(['limit' => 100, 'expand' => ['data.payment_intent']]);
                foreach ($sessions->autoPagingIterator() as $session) {
                    if (self::extraerPedidoIdDeSesionStripe($session) === (int) $this->id) {
                        $sesionesPedido[] = $session;
                    }
                    if ($this->created_at && $session->created < $this->created_at->copy()->subDay()->timestamp) {
                        break;
                    }
                }
            }

            $diagnostico['intentos_count'] = count($sesionesPedido);

            if (!empty($sesionesPedido)) {
                $diagnostico['encontrado'] = true;

                // Priorizar una sesión pagada si existe; de lo contrario usar la más reciente
                $sesionPrincipal = $sesionesPedido[0];
                $primeraSesion = $sesionesPedido[count($sesionesPedido) - 1];
                foreach ($sesionesPedido as $s) {
                    if (($s->payment_status ?? '') === 'paid') {
                        $sesionPrincipal = $s;
                        break;
                    }
                }

                // Construir historial de intentos con Hora de México (America/Mexico_City)
                foreach ($sesionesPedido as $idx => $sHist) {
                    $piHist = is_object($sHist->payment_intent ?? null) ? $sHist->payment_intent : null;
                    $errHist = $piHist->last_payment_error ?? null;
                    $estadoHist = 'Abandonado sin pagar ($0.00)';
                    if (($sHist->payment_status ?? '') === 'paid') {
                        $estadoHist = 'Pagado ($' . number_format(($sHist->amount_total ?? 0) / 100, 2) . ' MXN)';
                    } elseif ($errHist) {
                        $estadoHist = 'Tarjeta rechazada: ' . self::traducirCodigoRechazoStripe($errHist->decline_code ?? null, $errHist->code ?? null, $errHist->message ?? null);
                    } elseif ($this->estado === 'cancelado') {
                        $estadoHist = 'Cancelado por el cliente sin pagar ($0.00)';
                    } elseif (($sHist->status ?? '') === 'open') {
                        $estadoHist = 'Ventana de pago abierta ($0.00)';
                    } elseif (($sHist->status ?? '') === 'expired') {
                        $estadoHist = 'Sesión expirada / cancelada sin pago ($0.00)';
                    }

                    $fechaIntentoMx = isset($sHist->created)
                        ? \Carbon\Carbon::createFromTimestamp($sHist->created, 'UTC')->setTimezone('America/Mexico_City')->format('d/m/Y h:i:s A')
                        : '-';

                    $diagnostico['historial_intentos'][] = [
                        'numero'     => count($sesionesPedido) - $idx,
                        'session_id' => $sHist->id,
                        'fecha'      => $fechaIntentoMx,
                        'monto'      => ($sHist->amount_total ?? 0) / 100,
                        'pagado'     => ($sHist->payment_status ?? '') === 'paid',
                        'resumen'    => $estadoHist,
                    ];
                }

                $esPagado = ($sesionPrincipal->payment_status ?? '') === 'paid';
                $montoSesion = ($sesionPrincipal->amount_total ?? 0) / 100;

                $diagnostico['pagado_real']        = $esPagado;
                $diagnostico['monto_cobrado']      = $esPagado ? $montoSesion : 0.00;
                $diagnostico['moneda']             = strtoupper($sesionPrincipal->currency ?? 'MXN');
                $diagnostico['session_id']         = $sesionPrincipal->id;
                $diagnostico['entorno']            = !empty($sesionPrincipal->livemode) ? 'Producción (Live)' : 'Pruebas (Test)';
                $diagnostico['session_status_raw'] = $sesionPrincipal->status ?? null;
                $diagnostico['payment_status_raw'] = $sesionPrincipal->payment_status ?? 'unpaid';
                $diagnostico['fecha_sesion']       = isset($sesionPrincipal->created)
                    ? \Carbon\Carbon::createFromTimestamp($sesionPrincipal->created, 'UTC')->setTimezone('America/Mexico_City')->format('d/m/Y h:i:s A')
                    : $diagnostico['fecha_sesion'];

                if (($sesionPrincipal->status ?? '') === 'complete' && $esPagado) {
                    $diagnostico['session_status_label'] = 'Completada y Pagada';
                    $diagnostico['payment_status_label'] = 'Cobrado ($' . number_format($montoSesion, 2) . ' MXN)';
                } elseif (($sesionPrincipal->status ?? '') === 'expired') {
                    $diagnostico['session_status_label'] = 'Expirada / Abandonada';
                    $diagnostico['payment_status_label'] = 'Sin cobro ($0.00 MXN)';
                } else {
                    $diagnostico['session_status_label'] = 'Abierta (Sin completar pago)';
                    $diagnostico['payment_status_label'] = 'Sin cobro ($0.00 MXN)';
                }

                // Inspeccionar PaymentIntent si existe
                $pi = is_object($sesionPrincipal->payment_intent ?? null) ? $sesionPrincipal->payment_intent : null;
                if (!$pi && is_string($sesionPrincipal->payment_intent ?? null) && !empty($sesionPrincipal->payment_intent)) {
                    try {
                        $pi = $stripe->paymentIntents->retrieve($sesionPrincipal->payment_intent, [
                            'expand' => ['latest_charge', 'payment_method'],
                        ]);
                    } catch (\Exception $e) {
                        $pi = null;
                    }
                }

                if ($pi) {
                    $diagnostico['payment_intent_id'] = $pi->id ?? null;
                    $lastError = $pi->last_payment_error ?? null;

                    // Extraer datos de tarjeta si están disponibles
                    $cardObj = $lastError->payment_method->card
                        ?? $pi->latest_charge->payment_method_details->card
                        ?? $pi->payment_method->card
                        ?? null;

                    if ($cardObj) {
                        $marca = strtoupper($cardObj->brand ?? 'Tarjeta');
                        $last4 = $cardObj->last4 ?? '****';
                        $funding = ($cardObj->funding ?? '') === 'credit' ? 'Crédito' : (($cardObj->funding ?? '') === 'debit' ? 'Débito' : '');
                        $diagnostico['tarjeta_info'] = trim("{$marca} terminación {$last4}" . ($funding ? " ({$funding})" : ''));
                    }

                    if ($esPagado) {
                        $diagnostico['motivo_categoria']   = 'pagado';
                        $diagnostico['motivo_titulo']      = 'Pago Aprobado y Cobrado Exitosamente en Stripe';
                        $diagnostico['motivo_explicacion'] = 'Stripe procesó y autorizó el cobro por $' . number_format($montoSesion, 2) . ' MXN (' . $modalidadTexto . '). El pedido está listo para preparación y envío.';
                    } elseif ($lastError) {
                        $declineCode = $lastError->decline_code ?? $lastError->code ?? 'card_declined';
                        $motivoEsp = self::traducirCodigoRechazoStripe($lastError->decline_code ?? null, $lastError->code ?? null, $lastError->message ?? null);
                        $diagnostico['codigo_rechazo']     = $declineCode;
                        $diagnostico['motivo_categoria']   = 'rechazo_bancario';
                        $diagnostico['motivo_titulo']      = 'Cancelado: Pago Rechazado por el Banco (' . strtoupper($declineCode) . ')';
                        $diagnostico['motivo_explicacion'] = $motivoEsp . ' El cliente intentó pagar en Stripe con ' . $diagnostico['tarjeta_info'] . ', pero el cargo no fue autorizado. Monto cobrado en Stripe: $0.00 MXN.';
                    } elseif (($pi->status ?? '') === 'requires_action') {
                        $diagnostico['codigo_rechazo']     = '3d_secure_incomplete';
                        $diagnostico['motivo_categoria']   = '3d_secure_incompleto';
                        $diagnostico['motivo_titulo']      = 'Cancelado: Verificación 3D Secure del Banco No Completada';
                        $diagnostico['motivo_explicacion'] = 'El cliente ingresó su tarjeta (' . $diagnostico['tarjeta_info'] . '), pero su banco solicitó confirmación de seguridad 3D Secure (código SMS o autorización en app bancaria) y el cliente no la finalizó. Monto cobrado en Stripe: $0.00 MXN.';
                    } else {
                        $diagnostico['codigo_rechazo']     = 'abandoned_checkout';
                        $diagnostico['motivo_categoria']   = 'abandonado_cliente';
                        $diagnostico['motivo_titulo']      = 'Cancelado: El Cliente Abandonó Stripe sin Pagar';
                        $diagnostico['motivo_explicacion'] = 'El cliente abrió el formulario de Stripe Checkout, pero salió o regresó a la tienda sin autorizar el pago con tarjeta. En Stripe el cobro es de $0.00 MXN.';
                    }
                } else {
                    // No hay PaymentIntent (el cliente ni siquiera dio clic en "Pagar" dentro de Stripe)
                    $diagnostico['codigo_rechazo']   = 'no_payment_attempt';
                    $diagnostico['motivo_categoria'] = 'abandonado_cliente';
                    if (($sesionPrincipal->status ?? '') === 'expired' || $this->estado === 'cancelado') {
                        $diagnostico['motivo_titulo']      = 'Cancelado: El Cliente Salió de Stripe sin Ingresar Tarjeta';
                        $diagnostico['motivo_explicacion'] = 'Se generó la sesión de Stripe (' . $sesionPrincipal->id . '), pero el cliente cerró la ventana o presionó "Regresar a la tienda" antes de ingresar los datos de su tarjeta. Por eso en Stripe aparece con $0.00 MXN cobrados.';
                    } else {
                        $diagnostico['motivo_titulo']      = 'Pago No Completado: Sesión Abierta sin Ingreso de Tarjeta';
                        $diagnostico['motivo_explicacion'] = 'El cliente abrió la pasarela de Stripe (' . $sesionPrincipal->id . '), pero no ha ingresado datos de tarjeta ni completado el pago ($0.00 MXN cobrados en Stripe).';
                    }
                }

            } else {
                if ($this->estado === 'contacto_agente') {
                    $diagnostico['motivo_categoria']   = 'contacto_agente';
                    $diagnostico['motivo_titulo']      = 'Solicitud de Contacto con Agente de Ventas (Sin Cobro en Stripe)';
                    $diagnostico['motivo_explicacion'] = 'El cliente solicitó atención personalizada por WhatsApp con un asesor de ventas en lugar de completar el pago en línea ($0.00 MXN cobrados en Stripe).';
                } elseif (in_array($this->estado, ['pagado', 'completado', 'enviado', 'entregado'])) {
                    $diagnostico['motivo_categoria']   = 'aprobado_manual';
                    $diagnostico['motivo_titulo']      = 'Aprobado Manualmente por Administración';
                    $diagnostico['motivo_explicacion'] = 'Este pedido fue marcado como pagado/aprobado directamente desde el panel de administración (ej. pago por transferencia SPEI o acuerdo directo).';
                } else {
                    $diagnostico['motivo_categoria']   = 'abandonado_cliente';
                    $diagnostico['motivo_titulo']      = 'Cancelado: Pedido sin Pago Registrado en Stripe ($0.00 MXN)';
                    $diagnostico['motivo_explicacion'] = 'Este pedido no cuenta con ningún cobro completado en Stripe (fue cancelado antes de realizar el pago o generado durante pruebas previas sin cargo a tarjeta).';
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Error consultando diagnóstico Stripe para pedido #{$this->id}: " . $e->getMessage());
        }

        return $diagnostico;
    }
}
