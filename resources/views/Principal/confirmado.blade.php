@extends('layouts.app')

@section('titulo', '¡Pedido Confirmado! | Sector Mueble')

@section('contenido')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
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

        <!-- Ticket / Recibo de Compra -->
        <div class="bg-white border border-zinc-200 rounded-lg shadow-md overflow-hidden">
            <!-- Header Ticket -->
            <div class="bg-zinc-950 text-white p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                <div>
                    <span class="text-xs font-semibold tracking-wider text-amber-500 uppercase">Recibo de Pedido</span>
                    <h2 class="text-lg font-mono tracking-widest mt-1">Nº #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</h2>
                </div>
                <div class="mt-4 sm:mt-0 text-left sm:text-right">
                    <span class="block text-xs text-zinc-400">Fecha de compra</span>
                    <span class="block text-sm font-semibold mt-0.5">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
                <!-- Info Comprador y Envío -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm pb-8 border-b border-zinc-150">
                    <div>
                        <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Comprador</h3>
                        <p class="font-semibold text-zinc-900">{{ $pedido->nombre_cliente }}</p>
                        <p class="text-zinc-500 text-xs mt-1">{{ $pedido->correo_cliente }}</p>
                        <p class="text-zinc-500 text-xs">{{ $pedido->telefono_cliente }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Dirección de Entrega</h3>
                        <p class="font-semibold text-zinc-900">{{ $pedido->direccion_envio }}</p>
                        <p class="text-zinc-500 text-xs mt-1">{{ $pedido->codigo_postal }}, {{ $pedido->ciudad }}</p>
                        <p class="text-zinc-500 text-xs">Estado de entrega: <span class="font-bold text-amber-800 uppercase">{{ $pedido->estado }}</span></p>
                    </div>
                </div>

                <!-- Detalle de Artículos -->
                <div>
                    <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4">Artículos del Pedido</h3>
                    
                    <div class="divide-y divide-zinc-100">
                        @php
                            $subtotalCalculado = 0;
                        @endphp
                        @foreach($pedido->detalles as $det)
                            @php
                                $itemTotal = $det->precio * $det->cantidad;
                                $subtotalCalculado += $itemTotal;
                            @endphp
                            <div class="flex items-center justify-between py-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-zinc-900">{{ $det->nombre_producto }}</h4>
                                    <span class="text-xs text-zinc-500 font-medium">Cantidad: {{ $det->cantidad }} x {{ number_format($det->precio, 2, ',', '.') }} €</span>
                                </div>
                                <span class="text-sm font-bold text-zinc-900 font-sans">{{ number_format($itemTotal, 2, ',', '.') }} €</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Resumen de Costes -->
                @php
                    $costoEnvio = $pedido->total - $subtotalCalculado;
                @endphp
                <div class="bg-zinc-50 rounded p-4 space-y-2 text-xs border border-zinc-150">
                    <div class="flex justify-between text-zinc-500">
                        <span>Subtotal de productos</span>
                        <span class="font-semibold text-zinc-900 font-sans">{{ number_format($subtotalCalculado, 2, ',', '.') }} €</span>
                    </div>
                    <div class="flex justify-between text-zinc-500">
                        <span>Costo de envío</span>
                        @if($costoEnvio <= 0)
                            <span class="font-bold text-emerald-700 uppercase">Gratis</span>
                        @else
                            <span class="font-semibold text-zinc-900 font-sans">{{ number_format($costoEnvio, 2, ',', '.') }} €</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-sm text-zinc-950 pt-2 border-t border-zinc-200">
                        <span class="font-semibold">Total Pagado</span>
                        <span class="text-base font-bold font-sans">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Fin -->
        <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('catalogo') }}" class="w-full sm:w-auto text-center bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded transition-colors shadow">
                Seguir Comprando Muebles
            </a>
            <a href="{{ route('inicio') }}" class="w-full sm:w-auto text-center border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider px-8 py-4 rounded transition-colors">
                Volver a la Página Principal
            </a>
        </div>
    </div>
@endsection
