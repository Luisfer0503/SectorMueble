@extends('layouts.app')

@section('titulo', ($pedido->estado ?? '') === 'cancelado' ? 'Pedido Cancelado | Sector Mueble' : (($pedido->estado ?? '') === 'pendiente' ? 'Pedido Pendiente de Pago | Sector Mueble' : '¡Pedido Confirmado! | Sector Mueble'))

@section('contenido')
    @php
        $diagConfirmado = $diagnosticoStripe ?? null;
    @endphp
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if(($pedido->estado ?? '') === 'cancelado')
            <!-- Cancelled Banner -->
            <div class="text-center mb-12">
                <div class="inline-flex p-4 bg-rose-50 rounded-full text-rose-600 mb-4 border border-rose-200">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1 class="serif-title text-3xl sm:text-4xl font-bold text-rose-900">Pedido Cancelado / Pago No Completado</h1>
                <p class="mt-2 text-zinc-600 text-sm max-w-xl mx-auto">
                    {{ $diagConfirmado['motivo_explicacion'] ?? 'El proceso de pago en Stripe no fue completado o fue cancelado. No se ha realizado ningún cobro ($0.00 MXN) a tu tarjeta.' }}
                </p>
            </div>
        @elseif(($pedido->estado ?? '') === 'pendiente' || ($pedido->estado ?? '') === 'contacto_agente')
            <!-- Pending Banner -->
            <div class="text-center mb-12">
                <div class="inline-flex p-4 bg-amber-50 rounded-full text-amber-600 mb-4 border border-amber-200">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950">Pedido Pendiente de Pago</h1>
                <p class="mt-2 text-zinc-600 text-sm max-w-lg mx-auto">Tu orden está registrada en espera de confirmación de pago ($0.00 MXN cobrados hasta el momento).</p>
            </div>
        @else
            <!-- Success Banner -->
            <div class="text-center mb-12">
                <div class="inline-flex p-4 bg-emerald-50 rounded-full text-emerald-600 mb-4 border border-emerald-100">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950">¡Gracias por tu compra!</h1>
                <p class="mt-2 text-zinc-500 text-sm">Tu pedido ha sido procesado correctamente. Recibirás un correo electrónico de confirmación con los detalles del envío.</p>
            </div>
        @endif

        <!-- Ticket / Recibo de Compra -->
        <div class="bg-white border border-zinc-200 rounded-lg shadow-md overflow-hidden">
            <!-- Header Ticket -->
            <div class="{{ ($pedido->estado ?? '') === 'cancelado' ? 'bg-rose-950' : 'bg-zinc-950' }} text-white p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                <div>
                    @if(($pedido->estado ?? '') === 'cancelado')
                        <span class="text-xs font-bold tracking-wider text-rose-300 uppercase">✕ Pedido Cancelado · Sin Cobro ($0.00 MXN)</span>
                    @elseif(($pedido->estado ?? '') === 'pendiente' || ($pedido->estado ?? '') === 'contacto_agente')
                        <span class="text-xs font-bold tracking-wider text-amber-400 uppercase">⏳ Orden Pendiente de Pago</span>
                    @else
                        <span class="text-xs font-semibold tracking-wider text-amber-500 uppercase">Recibo de Pedido</span>
                    @endif
                    <h2 class="text-lg font-mono tracking-widest mt-1">Nº #{{ str_pad($pedido->id ?? 1, 5, '0', STR_PAD_LEFT) }}</h2>
                </div>
                <div class="mt-4 sm:mt-0 text-left sm:text-right">
                    <span class="block text-xs text-zinc-400">Fecha de registro</span>
                    <span class="block text-sm font-semibold mt-0.5">{{ \Carbon\Carbon::parse($pedido->created_at ?? now())->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
                <!-- Info Comprador y Envío -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm pb-8 border-b border-zinc-150">
                    <div>
                        <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Comprador</h3>
                        <p class="font-semibold text-zinc-900">{{ $pedido->nombre_cliente ?? 'Cliente' }}</p>
                        <p class="text-zinc-500 text-xs mt-1">{{ $pedido->correo_cliente ?? '' }}</p>
                        <p class="text-zinc-500 text-xs">{{ $pedido->telefono_cliente ?? '' }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Dirección de Entrega</h3>
                        <p class="font-semibold text-zinc-900">{{ $pedido->direccion_envio ?? 'Dirección no registrada' }}</p>
                        <p class="text-zinc-500 text-xs mt-1">{{ $pedido->codigo_postal ?? '' }}, {{ $pedido->ciudad ?? '' }}</p>
                        <p class="text-zinc-500 text-xs mt-0.5">
                            Estado del pedido: 
                            @if(($pedido->estado ?? '') === 'cancelado')
                                <span class="font-bold text-rose-700 uppercase">Cancelado (Sin cobro)</span>
                            @elseif(($pedido->estado ?? '') === 'pendiente')
                                <span class="font-bold text-amber-700 uppercase">Pendiente de Pago</span>
                            @else
                                <span class="font-bold text-emerald-800 uppercase">{{ $pedido->estado ?? 'procesando' }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Detalle de Artículos -->
                <div>
                    <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4">Artículos del Pedido</h3>
                    
                    <div class="divide-y divide-zinc-100">
                        @php
                            $subtotalCalculado = 0;
                            $detallesLista = $pedido->detalles ?? [];
                        @endphp
                        @forelse($detallesLista as $det)
                            @php
                                $itemTotal = ($det->precio ?? 0) * ($det->cantidad ?? 1);
                                $subtotalCalculado += $itemTotal;
                            @endphp
                            <div class="flex items-center justify-between py-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-zinc-900">{{ $det->nombre_producto ?? 'Producto' }}</h4>
                                    <span class="text-xs text-zinc-500 font-medium">Cantidad: {{ $det->cantidad ?? 1 }} x $ {{ number_format($det->precio ?? 0, 2, '.', ',') }} MXN</span>
                                </div>
                                <span class="text-sm font-bold text-zinc-900 font-sans">$ {{ number_format($itemTotal, 2, '.', ',') }}</span>
                            </div>
                        @empty
                            <div class="py-4 text-xs text-zinc-500 italic">
                                Tu pedido fue recibido y procesado con éxito.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Resumen de Costes -->
                @php
                    $descuentoPedido = (float) ($pedido->descuento ?? 0);
                    $costoEnvio = max(0, round(($pedido->total ?? 0) - $subtotalCalculado + $descuentoPedido, 2));
                    $tipoPagoRaw = (string) ($pedido->tipo_pago ?? 'contado');
                    $esMsi = str_starts_with($tipoPagoRaw, 'msi_');
                    $plazoMsi = $esMsi ? (int) str_replace('msi_', '', $tipoPagoRaw) : 0;
                @endphp
                <div class="bg-zinc-50 rounded p-4 space-y-2 text-xs border border-zinc-150">
                    <div class="flex justify-between text-zinc-500">
                        <span>Modalidad de pago</span>
                        <span class="font-bold text-zinc-900">
                            @if($esMsi && $plazoMsi > 0)
                                {{ $plazoMsi }} Meses Sin Intereses (Stripe)
                            @else
                                Pago de Contado (Stripe)
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-zinc-500">
                        <span>Subtotal de productos</span>
                        <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($subtotalCalculado, 2, '.', ',') }}</span>
                    </div>
                    @if($descuentoPedido > 0)
                        <div class="flex justify-between text-rose-700 font-medium">
                            <span>Descuento aplicado{{ !empty($pedido->cupon_codigo) ? ' (Cupón: ' . $pedido->cupon_codigo . ')' : '' }}</span>
                            <span class="font-sans">-$ {{ number_format($descuentoPedido, 2, '.', ',') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-zinc-500">
                        <span>Costo de envío</span>
                        @if($costoEnvio <= 0)
                            <span class="font-bold text-emerald-700 uppercase">Gratis</span>
                        @else
                            <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($costoEnvio, 2, '.', ',') }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-sm text-zinc-950 pt-2 border-t border-zinc-200">
                        <span class="font-semibold">{{ ($pedido->estado ?? '') === 'cancelado' ? 'Valor del Carrito (Cancelado)' : (($pedido->estado ?? '') === 'pendiente' ? 'Total por Pagar' : 'Total Pagado') }}</span>
                        <span class="text-base font-bold font-sans {{ ($pedido->estado ?? '') === 'cancelado' ? 'text-zinc-400 line-through' : 'text-emerald-900' }}">$ {{ number_format($pedido->total ?? 0, 2, '.', ',') }} MXN</span>
                    </div>
                    @if(($pedido->estado ?? '') === 'cancelado' || ($pedido->estado ?? '') === 'pendiente')
                        <div class="flex justify-between items-center text-sm pt-2 border-t border-zinc-200">
                            <span class="font-extrabold text-rose-800">Total Cobrado en Tarjeta (Stripe)</span>
                            <span class="text-base font-extrabold font-sans text-rose-700">$ 0.00 MXN</span>
                        </div>
                    @endif
                </div>

                <!-- Bloque de Facturación Electrónica SAT (Oculto temporalmente) -->
                <div class="hidden pt-4 border-t border-zinc-150">
                    <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block">Facturación Electrónica SAT (CFDI 4.0)</span>
                            @if(($pedido->factura_estado ?? '') === 'facturado')
                                <span class="text-xs font-extrabold text-emerald-700 mt-0.5 block">¡Factura emitida correctamente!</span>
                                <span class="text-[11px] font-mono text-zinc-600 block">UUID: {{ $pedido->factura_uuid }}</span>
                            @elseif(($pedido->factura_estado ?? '') === 'pendiente')
                                <span class="text-xs font-bold text-amber-700 mt-0.5 block">Factura en proceso de timbrado...</span>
                            @else
                                <span class="text-xs text-zinc-600 mt-0.5 block">¿Requieres comprobante fiscal digital para esta compra?</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if(($pedido->factura_estado ?? '') === 'facturado')
                                <a href="{{ route('facturacion.descargar.pdf', $pedido->id) }}" target="_blank" class="px-3 py-1.5 bg-emerald-700 text-white rounded-lg text-xs font-bold hover:bg-emerald-800 shadow-xs">Descargar PDF</a>
                                <a href="{{ route('facturacion.descargar.xml', $pedido->id) }}" target="_blank" class="px-3 py-1.5 bg-zinc-800 text-white rounded-lg text-xs font-bold hover:bg-zinc-900 shadow-xs">Descargar XML</a>
                            @else
                                <a href="{{ route('facturacion.index', ['pedido_id' => $pedido->id ?? '', 'correo' => $pedido->correo_cliente ?? '']) }}" class="px-4 py-2 bg-[#4c6f4f] text-white rounded-xl text-xs font-bold hover:bg-[#3c583e] shadow-xs">
                                    Solicitar / Generar Factura
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Fin -->
        <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
            @if(($pedido->estado ?? '') === 'cancelado')
                <a href="{{ route('carrito') }}" class="w-full sm:w-auto text-center bg-[#88674B] hover:bg-[#6f533b] text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Reintentar Compra / Ir al Carrito</span>
                </a>
            @elseif(isset($pedido->id))
                <a href="{{ route('pedido.contactar_agente', $pedido->id) }}" target="_blank" class="w-full sm:w-auto text-center bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>Contactar Agente de Ventas</span>
                </a>
            @endif
            <a href="{{ route('catalogo') }}" class="w-full sm:w-auto text-center bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-xl transition-colors shadow">
                Seguir Comprando Muebles
            </a>
            <a href="{{ route('inicio') }}" class="w-full sm:w-auto text-center border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-xl transition-colors">
                Volver a la Página Principal
            </a>
        </div>
    </div>
@endsection
