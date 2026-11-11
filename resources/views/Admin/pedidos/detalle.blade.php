@extends('layouts.admin')

@section('contenido')
    @php
        $diag = $diagnosticoStripe ?? $pedido->obtenerDiagnosticoStripe(false);
        $esCancelado = $pedido->estado === 'cancelado';
        $esPendiente = $pedido->estado === 'pendiente';
        $esContactoAgente = $pedido->estado === 'contacto_agente';
        $esPagadoConfirmado = in_array($pedido->estado, ['pagado', 'procesando', 'completado', 'procesado', 'enviado', 'entregado', 'recibido']);
        $folioPedido = str_pad($pedido->id, 5, '0', STR_PAD_LEFT);

        // Preparar teléfono para botón directo de WhatsApp
        $telDigitos = preg_replace('/[^0-9]/', '', (string) $pedido->telefono_cliente);
        if (strlen($telDigitos) === 10) {
            $telDigitos = '52' . $telDigitos;
        }
        $msgWhatsapp = rawurlencode("Hola {$pedido->nombre_cliente}, te escribimos de Sector Mueble respecto a tu pedido #{$folioPedido}.");
    @endphp

    <!-- Header with breadcrumbs -->
    <div class="pb-6 border-b border-zinc-200 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex text-xs font-medium text-zinc-500 space-x-2 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-850">Inicio</a>
                <span>/</span>
                <a href="{{ route('admin.pedidos') }}" class="hover:text-amber-850">Pedidos</a>
                <span>/</span>
                <span class="text-zinc-800">Pedido #{{ $folioPedido }}</span>
            </nav>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="serif-title text-3xl font-bold text-zinc-950">Pedido Nº #{{ $folioPedido }}</h1>
                @if($esCancelado)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                        ✕ CANCELADO · SIN COBRO ($0.00 MXN)
                    </span>
                @elseif($esPendiente)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                        ⏳ PENDIENTE DE PAGO ($0.00 COBRADO)
                    </span>
                @elseif($esContactoAgente)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-300">
                        💬 CONTACTO AGENTE DE VENTAS
                    </span>
                @elseif($pedido->estado === 'enviado')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-300">
                        📦 ENVIADO
                    </span>
                @elseif($pedido->estado === 'entregado' || $pedido->estado === 'recibido')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300">
                        🎉 ENTREGADO
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                        ✓ PAGADO / PROCESANDO
                    </span>
                @endif
            </div>
            <p class="text-zinc-500 text-xs mt-1.5">
                Registrado el <strong>{{ $pedido->created_at_mexico->format('d/m/Y \a \l\a\s h:i:s A') }} (Hora de México)</strong>
                ({{ $pedido->created_at_mexico->diffForHumans() }})
                @if($pedido->updated_at && $pedido->updated_at->ne($pedido->created_at))
                    · Última actualización: {{ $pedido->updated_at_mexico->format('d/m/Y h:i:s A') }}
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('pedido.confirmado', $pedido->id) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded border border-zinc-300 bg-white hover:bg-zinc-50 text-xs font-bold text-zinc-700 shadow-2xs transition-colors">
                🔗 Ver Comprobante Público
            </a>
            <a href="{{ route('admin.pedidos') }}" class="inline-flex items-center px-3.5 py-2 rounded border border-zinc-200 bg-zinc-100 hover:bg-zinc-200 text-xs font-bold text-zinc-700 transition-colors">
                &larr; Volver a Pedidos
            </a>
        </div>
    </div>

    <!-- BANNER PRINCIPAL: Diagnóstico de Cobranza y Motivo de Cancelación (Stripe) -->
    <div class="mb-8 rounded-lg border-2 p-6 shadow-xs {{ $esCancelado ? 'bg-rose-50/70 border-rose-300' : ($esPendiente || $esContactoAgente ? 'bg-amber-50/70 border-amber-300' : 'bg-emerald-50/70 border-emerald-300') }}">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="space-y-2 flex-1">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded {{ $esCancelado ? 'bg-rose-200 text-rose-900' : ($esPendiente || $esContactoAgente ? 'bg-amber-200 text-amber-900' : 'bg-emerald-200 text-emerald-900') }}">
                        Diagnóstico de Cobranza Stripe
                    </span>
                    <span class="text-xs font-mono text-zinc-500">
                        {{ $diag['entorno'] ?? 'Stripe' }}
                    </span>
                </div>

                <h2 class="text-lg font-bold {{ $esCancelado ? 'text-rose-950' : ($esPendiente || $esContactoAgente ? 'text-amber-950' : 'text-emerald-950') }}">
                    {{ $diag['motivo_titulo'] ?? ($esCancelado ? 'Pedido Cancelado — Sin Cobro ($0.00 MXN)' : 'Estado del Pago') }}
                </h2>

                <p class="text-sm leading-relaxed {{ $esCancelado ? 'text-rose-900' : ($esPendiente || $esContactoAgente ? 'text-amber-900' : 'text-emerald-900') }}">
                    {{ $diag['motivo_explicacion'] ?? '' }}
                </p>

                @if(!empty($diag['codigo_rechazo']))
                    <div class="pt-1 flex flex-wrap items-center gap-2 text-xs">
                        <span class="font-semibold text-zinc-700">Código / Evento detectado en Stripe:</span>
                        <code class="px-2 py-0.5 bg-white border border-zinc-300 rounded font-mono font-bold text-rose-800">
                            {{ $diag['codigo_rechazo'] }}
                        </code>
                    </div>
                @endif
            </div>

            <!-- Comparativa Rápida: Valor del Pedido vs Cobrado en Stripe -->
            <div class="grid grid-cols-2 gap-3 sm:min-w-[320px] bg-white p-4 rounded-lg border border-zinc-200 shadow-2xs shrink-0">
                <div class="pr-3 border-r border-zinc-150">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400">Valor del Carrito</span>
                    <span class="block text-base font-bold font-sans mt-1 {{ $esCancelado ? 'text-zinc-400 line-through' : 'text-zinc-900' }}">
                        $ {{ number_format($pedido->total, 2, '.', ',') }} MXN
                    </span>
                    <span class="block text-[10px] text-zinc-500 mt-0.5">
                        {{ $diag['modalidad_texto'] ?? 'Contado (Stripe)' }}
                    </span>
                </div>
                <div class="pl-1">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400">Cobrado en Stripe</span>
                    @if($diag['pagado_real'] ?? false)
                        <span class="block text-base font-extrabold font-sans text-emerald-700 mt-1">
                            $ {{ number_format($diag['monto_cobrado'], 2, '.', ',') }} MXN
                        </span>
                        <span class="block text-[10px] font-bold text-emerald-700 mt-0.5">✓ Pago confirmado</span>
                    @elseif($esPagadoConfirmado)
                        <span class="block text-base font-extrabold font-sans text-emerald-700 mt-1">
                            $ {{ number_format($pedido->total, 2, '.', ',') }} MXN
                        </span>
                        <span class="block text-[10px] font-bold text-emerald-700 mt-0.5">✓ Aprobado manualmente</span>
                    @else
                        <span class="block text-base font-extrabold font-sans text-rose-700 mt-1">
                            $ 0.00 MXN
                        </span>
                        <span class="block text-[10px] font-bold text-rose-700 mt-0.5">✕ Sin cargo a tarjeta</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ficha Técnica de la Sesión en Stripe -->
        <div class="mt-5 pt-4 border-t {{ $esCancelado ? 'border-rose-200/80' : ($esPendiente || $esContactoAgente ? 'border-amber-200/80' : 'border-emerald-200/80') }} grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <div class="bg-white/80 rounded p-3 border border-zinc-200/80">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400">Estado de Sesión Stripe</span>
                <span class="font-bold text-zinc-900 block mt-0.5">{{ $diag['session_status_label'] ?? 'Sin sesión' }}</span>
                @if(!empty($diag['session_id']))
                    <span class="font-mono text-[10px] text-zinc-500 break-all block mt-0.5">{{ $diag['session_id'] }}</span>
                @endif
            </div>

            <div class="bg-white/80 rounded p-3 border border-zinc-200/80">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400">Tarjeta / Método de Pago</span>
                <span class="font-bold text-zinc-900 block mt-0.5">{{ $diag['tarjeta_info'] ?? 'No llegó a ingresar tarjeta' }}</span>
                @if(!empty($diag['payment_intent_id']))
                    <span class="font-mono text-[10px] text-zinc-500 break-all block mt-0.5">PI: {{ $diag['payment_intent_id'] }}</span>
                @else
                    <span class="text-[10px] text-zinc-500 block mt-0.5">Sin cargo procesado</span>
                @endif
            </div>

            <div class="bg-white/80 rounded p-3 border border-zinc-200/80">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400">Modalidad Elegida</span>
                <span class="font-bold text-zinc-900 block mt-0.5">{{ $diag['modalidad_texto'] ?? 'Pago de Contado' }}</span>
                <span class="text-[10px] text-zinc-500 block mt-0.5">
                    Intentos en Stripe: <strong>{{ $diag['intentos_count'] ?? 0 }}</strong>
                </span>
            </div>

            <div class="bg-white/80 rounded p-3 border border-zinc-200/80">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400">Fecha del Intento (Hora México)</span>
                <span class="font-bold text-zinc-900 block mt-0.5">{{ $diag['fecha_sesion'] ?? $pedido->created_at_mexico->format('d/m/Y h:i:s A') }}</span>
                <span class="text-[10px] text-zinc-500 block mt-0.5">
                    Suma en Ventas Netas: <strong>{{ $esPagadoConfirmado ? 'SÍ' : 'NO ($0.00)' }}</strong>
                </span>
            </div>
        </div>

        @if(!empty($diag['historial_intentos']) && count($diag['historial_intentos']) > 0)
            <div class="mt-4 pt-3 border-t {{ $esCancelado ? 'border-rose-200/70' : 'border-zinc-200/70' }}">
                <span class="block text-[11px] font-bold uppercase tracking-wider text-zinc-600 mb-2">
                    Bitácora de Sesiones en Stripe para este Pedido ({{ count($diag['historial_intentos']) }})
                </span>
                <div class="space-y-1.5">
                    @foreach($diag['historial_intentos'] as $intento)
                        <div class="flex flex-wrap items-center justify-between gap-2 bg-white/90 px-3 py-2 rounded border border-zinc-200 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-zinc-700">Intento #{{ $intento['numero'] }}</span>
                                <span class="text-zinc-400">·</span>
                                <span class="text-zinc-500">{{ $intento['fecha'] }}</span>
                                <span class="text-zinc-400">·</span>
                                <code class="text-[10px] font-mono text-zinc-500">{{ $intento['session_id'] }}</code>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-zinc-600 font-sans">Monto sesión: $ {{ number_format($intento['monto'], 2, '.', ',') }}</span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $intento['pagado'] ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $intento['resumen'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Detalles y Artículos (Izquierda - Col 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Artículos en este Pedido -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                    <h2 class="serif-title text-lg font-bold text-zinc-950">
                        Artículos en este Pedido ({{ $pedido->detalles->sum('cantidad') }} {{ $pedido->detalles->sum('cantidad') === 1 ? 'unidad' : 'unidades' }})
                    </h2>
                    <span class="text-xs text-zinc-500">
                        {{ $pedido->detalles->count() }} {{ $pedido->detalles->count() === 1 ? 'línea de producto' : 'líneas de productos' }}
                    </span>
                </div>
                
                <div class="divide-y divide-zinc-100 text-sm">
                    @php
                        $subtotalProductos = 0;
                    @endphp
                    @foreach($pedido->detalles as $det)
                        @php
                            $itemTotal = $det->precio * $det->cantidad;
                            $subtotalProductos += $itemTotal;
                            $prod = $det->producto;

                            // Extraer Acabado [Acabado] y Nota (Nota: ...) si vienen en nombre_producto
                            $nombreBase = $det->nombre_producto;
                            $acabadoExtraido = null;
                            $notaExtraida = null;

                            if (preg_match('/\[([^\]]+)\]/', $nombreBase, $mAcab)) {
                                $acabadoExtraido = $mAcab[1];
                            }
                            if (preg_match('/\(Nota:\s*([^)]+)\)/i', $nombreBase, $mNota)) {
                                $notaExtraida = $mNota[1];
                            }
                        @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-4 gap-4">
                            <div class="flex items-start space-x-4">
                                @if($prod && $prod->imagen_url)
                                    <img src="{{ asset($prod->imagen_url) }}" alt="{{ $det->nombre_producto }}" class="w-16 h-16 object-cover rounded border border-zinc-200 bg-zinc-50 shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded border border-zinc-200 bg-zinc-100 flex items-center justify-center text-zinc-400 text-xs font-bold shrink-0">
                                        SM
                                    </div>
                                @endif
                                <div class="space-y-1">
                                    <h4 class="font-semibold text-zinc-900 leading-snug">{{ $det->nombre_producto }}</h4>
                                    
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <span class="text-zinc-600 font-medium">
                                            {{ $det->cantidad }} {{ $det->cantidad == 1 ? 'unidad' : 'unidades' }} x $ {{ number_format($det->precio, 2, '.', ',') }} MXN
                                        </span>
                                        @if($acabadoExtraido)
                                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-900 border border-amber-200 font-semibold text-[11px]">
                                                🎨 Acabado: {{ $acabadoExtraido }}
                                            </span>
                                        @endif
                                        @if($notaExtraida)
                                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-900 border border-blue-200 font-semibold text-[11px]">
                                                📝 Nota: {{ $notaExtraida }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($prod)
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-zinc-500 pt-0.5">
                                            <span>Categoría: <strong class="text-zinc-700">{{ ucfirst($prod->categoria) }}</strong></span>
                                            @if($prod->proveedor)
                                                <span>· Proveedor: <strong class="text-zinc-700">{{ $prod->proveedor }}</strong></span>
                                            @endif
                                            <span>· Stock actual: <strong class="{{ $prod->stock <= 2 ? 'text-rose-600' : 'text-emerald-700' }}">{{ $prod->stock }} disp.</strong></span>
                                            <a href="{{ route('admin.productos.editar', $prod->id) }}" class="text-amber-800 hover:underline font-semibold">
                                                Ver en catálogo &rarr;
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-zinc-900 font-sans block text-sm">$ {{ number_format($itemTotal, 2, '.', ',') }} MXN</span>
                                @if($esCancelado)
                                    <span class="text-[10px] text-rose-600 font-semibold block">No cobrado ($0.00)</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen de Precios -->
                @php
                    $costoEnvio = max(0, round($pedido->total - $subtotalProductos + ($pedido->descuento ?? 0), 2));
                    $tpDetalle = (string) ($pedido->tipo_pago ?? 'contado');
                    $esMsiDetalle = str_starts_with($tpDetalle, 'msi_');
                    $plazoMsiDetalle = $esMsiDetalle ? (int) str_replace('msi_', '', $tpDetalle) : 0;
                @endphp
                <div class="bg-zinc-50 rounded border border-zinc-200 p-4 space-y-2.5 text-xs mt-6">
                    <div class="flex justify-between text-zinc-600">
                        <span>Modalidad seleccionada en Checkout</span>
                        <span class="font-bold {{ $esMsiDetalle ? 'text-indigo-800' : 'text-emerald-800' }}">
                            {{ $esMsiDetalle && $plazoMsiDetalle > 0 ? $plazoMsiDetalle . ' Meses Sin Intereses (Stripe)' : 'Pago de Contado (Stripe)' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-zinc-600">
                        <span>Subtotal de artículos ({{ $pedido->detalles->sum('cantidad') }} pzas)</span>
                        <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($subtotalProductos, 2, '.', ',') }} MXN</span>
                    </div>
                    @if(($pedido->descuento ?? 0) > 0 || !empty($pedido->cupon_codigo))
                        <div class="flex justify-between text-zinc-600">
                            <span>Descuento por cupón{{ $pedido->cupon_codigo ? ' (' . $pedido->cupon_codigo . ')' : '' }}</span>
                            <span class="font-semibold text-rose-700 font-sans">-$ {{ number_format($pedido->descuento ?? 0, 2, '.', ',') }} MXN</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-zinc-600">
                        <span>Costo de envío (CP: {{ $pedido->codigo_postal }})</span>
                        @if($costoEnvio <= 0)
                            <span class="font-bold text-emerald-700 uppercase">Gratis ($0.00 MXN)</span>
                        @else
                            <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($costoEnvio, 2, '.', ',') }} MXN</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-sm text-zinc-950 pt-2.5 border-t border-zinc-200">
                        <span class="font-bold">Valor Total del Pedido (Carrito)</span>
                        <span class="text-base font-bold font-sans {{ $esCancelado ? 'text-zinc-400 line-through' : 'text-zinc-900' }}">$ {{ number_format($pedido->total, 2, '.', ',') }} MXN</span>
                    </div>
                    <div class="flex justify-between items-center text-sm pt-2 border-t border-zinc-200">
                        <span class="font-extrabold {{ $esCancelado || $esPendiente ? 'text-rose-800' : 'text-emerald-800' }}">
                            Monto Real Cobrado (Ingreso Neto)
                        </span>
                        @if($esPagadoConfirmado)
                            <span class="text-base font-extrabold font-sans text-emerald-700">
                                $ {{ number_format($pedido->total, 2, '.', ',') }} MXN
                            </span>
                        @else
                            <span class="text-base font-extrabold font-sans text-rose-700">
                                $ 0.00 MXN
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Datos Detallados del Cliente y Entrega -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-zinc-100 mb-4">
                    <h2 class="serif-title text-lg font-bold text-zinc-950">Información del Cliente y Envío</h2>
                    @if($pedido->usuario)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-700 border border-zinc-200">
                            👤 Usuario Registrado (ID #{{ $pedido->usuario->id }} · {{ $pedido->usuario->email }})
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-600 border border-zinc-200">
                            👤 Compra como Invitado
                        </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div class="space-y-2">
                        <span class="block text-xs font-bold text-zinc-400 uppercase tracking-wider">Datos de Contacto</span>
                        <p class="font-bold text-zinc-900 text-base">{{ $pedido->nombre_cliente }}</p>
                        <p class="text-zinc-600 text-xs">
                            <strong>Correo:</strong>
                            <a href="mailto:{{ $pedido->correo_cliente }}" class="text-amber-800 hover:underline">{{ $pedido->correo_cliente }}</a>
                        </p>
                        <p class="text-zinc-600 text-xs">
                            <strong>Teléfono:</strong> {{ $pedido->telefono_cliente ?: 'No especificado' }}
                        </p>

                        @if(!empty($telDigitos))
                            <div class="pt-2">
                                <a href="https://wa.me/{{ $telDigitos }}?text={{ $msgWhatsapp }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-2xs transition-colors">
                                    💬 Contactar Cliente por WhatsApp
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <span class="block text-xs font-bold text-zinc-400 uppercase tracking-wider">Domicilio de Entrega</span>
                        <p class="font-semibold text-zinc-900 leading-relaxed">{{ $pedido->direccion_envio }}</p>
                        <div class="flex flex-wrap gap-3 text-xs text-zinc-600 pt-1">
                            <span class="bg-zinc-100 px-2.5 py-1 rounded border border-zinc-200">
                                <strong>C.P.:</strong> {{ $pedido->codigo_postal }}
                            </span>
                            <span class="bg-zinc-100 px-2.5 py-1 rounded border border-zinc-200">
                                <strong>Ciudad / Estado:</strong> {{ $pedido->ciudad }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Facturación Electrónica SAT (FastAPI) -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                    <h2 class="serif-title text-lg font-bold text-zinc-950">Facturación Electrónica SAT (FastAPI)</h2>
                    @if($pedido->factura_estado === 'facturado')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Facturado</span>
                    @elseif($pedido->factura_estado === 'error')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">Error en Timbrado</span>
                    @elseif($pedido->factura_estado === 'pendiente')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">Pendiente</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-zinc-100 text-zinc-600 border border-zinc-200">No Solicitada</span>
                    @endif
                </div>

                @if($esCancelado || $esPendiente)
                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded text-xs text-amber-900">
                        ⚠️ <strong>Nota:</strong> Este pedido se encuentra <strong>{{ strtoupper($pedido->estado) }}</strong> ($0.00 cobrados en Stripe). Verifica que el pago haya sido recibido antes de emitir una factura fiscal ante el SAT.
                    </div>
                @endif

                @if($pedido->factura_estado === 'facturado')
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2 text-xs text-emerald-900 font-mono">
                        <p><strong>Folio Fiscal (UUID):</strong> {{ $pedido->factura_uuid }}</p>
                        <p><strong>RFC Receptor:</strong> {{ $pedido->rfc_receptor }}</p>
                        <p><strong>Razón Social:</strong> {{ $pedido->razon_social }}</p>
                        <div class="pt-2 flex gap-3">
                            <a href="{{ route('facturacion.descargar.pdf', $pedido->id) }}" target="_blank" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-sans font-bold rounded shadow-xs">Ver PDF</a>
                            <a href="{{ route('facturacion.descargar.xml', $pedido->id) }}" target="_blank" class="px-3 py-1.5 bg-zinc-800 hover:bg-zinc-900 text-white font-sans font-bold rounded shadow-xs">Ver XML</a>
                        </div>
                    </div>
                @else
                    <form action="{{ route('admin.pedidos.facturar', $pedido->id) }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        @if($pedido->factura_error)
                            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded font-mono">
                                <strong>Error FastAPI:</strong> {{ $pedido->factura_error }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">RFC Receptor</label>
                                <input type="text" name="rfc_receptor" value="{{ old('rfc_receptor', $pedido->rfc_receptor) }}" placeholder="XAXX010101000" maxlength="13" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2 uppercase font-mono">
                            </div>
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Razón Social</label>
                                <input type="text" name="razon_social" value="{{ old('razon_social', $pedido->razon_social) }}" placeholder="Nombre o Razón Social" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2 uppercase">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Régimen Fiscal</label>
                                <select name="regimen_fiscal" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2">
                                    <option value="601" {{ $pedido->regimen_fiscal === '601' ? 'selected' : '' }}>601 - General de Ley Personas Morales</option>
                                    <option value="605" {{ $pedido->regimen_fiscal === '605' ? 'selected' : '' }}>605 - Sueldos y Salarios</option>
                                    <option value="606" {{ $pedido->regimen_fiscal === '606' ? 'selected' : '' }}>606 - Arrendamiento</option>
                                    <option value="612" {{ $pedido->regimen_fiscal === '612' ? 'selected' : '' }}>612 - Personas Físicas Act. Empresariales</option>
                                    <option value="616" {{ ($pedido->regimen_fiscal ?? '616') === '616' ? 'selected' : '' }}>616 - Sin obligaciones fiscales</option>
                                    <option value="626" {{ $pedido->regimen_fiscal === '626' ? 'selected' : '' }}>626 - RESICO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Uso de CFDI</label>
                                <select name="uso_cfdi" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2">
                                    <option value="G01" {{ $pedido->uso_cfdi === 'G01' ? 'selected' : '' }}>G01 - Adquisición de mercancías</option>
                                    <option value="G03" {{ ($pedido->uso_cfdi ?? 'G03') === 'G03' ? 'selected' : '' }}>G03 - Gastos en general</option>
                                    <option value="S01" {{ $pedido->uso_cfdi === 'S01' ? 'selected' : '' }}>S01 - Sin efectos fiscales</option>
                                    <option value="CP01" {{ $pedido->uso_cfdi === 'CP01' ? 'selected' : '' }}>CP01 - Pagos</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">CP Fiscal</label>
                                <input type="text" name="codigo_postal_fiscal" value="{{ old('codigo_postal_fiscal', $pedido->codigo_postal_fiscal ?? $pedido->codigo_postal) }}" maxlength="5" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2 font-mono">
                            </div>
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Correo Envío</label>
                                <input type="email" name="correo_facturacion" value="{{ old('correo_facturacion', $pedido->correo_facturacion ?? $pedido->correo_cliente) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#4c6f4f] hover:bg-[#3c583e] text-white font-bold text-xs uppercase tracking-wider py-3 rounded transition-colors shadow">
                            ⚡ Emitir Factura SAT vía FastAPI
                        </button>
                    </form>
                @endif
            </div>

        </div>

        <!-- Estado y Gestión (Derecha - Col 1) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Cambio de Estado -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm sticky top-24">
                <h2 class="serif-title text-lg font-bold text-zinc-950 pb-3 border-b border-zinc-100 mb-4">Gestión del Pedido</h2>
                
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-xs text-zinc-500">Estado actual:</span>
                    @if(in_array($pedido->estado, ['pagado', 'procesando', 'completado', 'procesado']))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-300">✓ Pagado / Procesando</span>
                    @elseif($pedido->estado === 'pendiente')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">⏳ Pendiente de Pago</span>
                    @elseif($pedido->estado === 'enviado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-800 border border-indigo-200">📦 Enviado</span>
                    @elseif($pedido->estado === 'entregado' || $pedido->estado === 'recibido')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300">🎉 Entregado</span>
                    @elseif($pedido->estado === 'contacto_agente')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-800 border border-purple-200">💬 Contacto Agente</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">✕ Cancelado ($0.00)</span>
                    @endif
                </div>

                <div class="mb-5 p-3 rounded border text-xs {{ $esCancelado ? 'bg-rose-50 border-rose-200 text-rose-900' : ($esPendiente ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900') }}">
                    @if($esCancelado)
                        <strong>Pedido Cancelado:</strong> No suma en Ventas Netas y su comprobante público (<code>/pedido-confirmado/{{ $pedido->id }}</code>) muestra que fue cancelado sin cargo ($0.00 MXN).
                    @elseif($esPendiente)
                        <strong>Pago Pendiente:</strong> El cliente aún no ha completado el pago en Stripe ($0.00 MXN). No suma en Ventas Netas.
                    @else
                        <strong>Pedido Activo:</strong> Este pedido está confirmado y sí se contabiliza dentro de tus Ventas Netas.
                    @endif
                </div>

                <form action="{{ route('admin.pedidos.actualizar_estado', $pedido->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="estado" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Cambiar Estado Manualmente</label>
                        <select name="estado" id="estado" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>✕ Cancelado (Sin cobro / No completado)</option>
                            <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>⏳ Pendiente de Pago</option>
                            <option value="contacto_agente" {{ $pedido->estado === 'contacto_agente' ? 'selected' : '' }}>💬 Contacto a Agente de Ventas</option>
                            <option value="pagado" {{ in_array($pedido->estado, ['procesando', 'pagado', 'completado', 'procesado']) ? 'selected' : '' }}>✓ Pagado / Procesando Compra</option>
                            <option value="enviado" {{ $pedido->estado === 'enviado' ? 'selected' : '' }}>📦 Enviado (En camino - Notifica por Correo)</option>
                            <option value="entregado" {{ ($pedido->estado === 'entregado' || $pedido->estado === 'recibido') ? 'selected' : '' }}>🎉 Entregado / Recibido (Notifica por Correo)</option>
                        </select>
                        <p class="text-[11px] text-zinc-400 mt-1.5 leading-tight">
                            📧 Al cambiar el estado (excepto a Cancelado), se notificará por correo a <strong>{{ $pedido->correo_cliente }}</strong>.
                        </p>
                    </div>

                    <button type="submit" class="w-full bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded transition-colors shadow">
                        Guardar Estado del Pedido
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-zinc-150 space-y-2 text-center">
                    <a href="{{ route('pedido.confirmado', $pedido->id) }}" target="_blank" class="block text-xs font-bold text-zinc-600 hover:text-amber-850 hover:underline">
                        🔗 Abrir recibo público (/pedido-confirmado/{{ $pedido->id }})
                    </a>
                    <a href="{{ route('admin.pedidos') }}" class="block text-xs font-bold text-zinc-500 hover:text-amber-850 hover:underline">
                        &larr; Volver al listado de pedidos
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
