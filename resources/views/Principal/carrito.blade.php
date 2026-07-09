@extends('layouts.app')

@section('titulo', 'Tu Carrito | Sector Mueble')

@section('contenido')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="serif-title text-3xl font-bold text-zinc-950 mb-8">Tu Carrito de Compras</h1>

        @if(empty($carrito))
            <!-- Carrito Vacío -->
            <div class="text-center py-20 bg-white border border-zinc-200 rounded p-8 max-w-lg mx-auto">
                <svg class="h-16 w-16 text-zinc-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="mt-6 text-lg font-semibold text-zinc-950">Tu carrito está vacío</h3>
                <p class="mt-2 text-zinc-500 text-sm">¿Aún no te decides? Explora nuestro catálogo de muebles y encuentra las piezas perfectas para tu hogar.</p>
                <a href="{{ route('catalogo') }}" class="mt-8 inline-block bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded transition-colors shadow">
                    Explorar Muebles
                </a>
            </div>
        @else
            <!-- Carrito con Artículos -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Lista de Artículos (Izquierda) -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($carrito as $id => $item)
                        <div class="bg-white border border-zinc-200 rounded p-4 sm:p-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between shadow-sm hover:shadow-md transition-shadow">
                            <!-- Producto Info -->
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-20 bg-zinc-100 rounded overflow-hidden flex-shrink-0">
                                    <img src="{{ $item['imagen_url'] }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">{{ $item['categoria'] }}</span>
                                    <h3 class="text-sm font-semibold text-zinc-950 mt-0.5">
                                        <a href="{{ route('productos.detalle', $id) }}" class="hover:text-amber-800 transition-colors">
                                            {{ $item['nombre'] }}
                                        </a>
                                    </h3>
                                    <span class="text-xs text-zinc-500 font-sans block mt-1">{{ number_format($item['precio'], 2, ',', '.') }} € c/u</span>
                                </div>
                            </div>

                            <!-- Modificar Cantidad & Precio Total -->
                            <div class="flex items-center justify-between sm:justify-end space-x-8 mt-4 sm:mt-0 pt-4 sm:pt-0 border-t border-zinc-100 sm:border-t-0">
                                <!-- Form de Cantidad -->
                                <form action="{{ route('carrito.actualizar', $id) }}" method="POST" class="flex items-center border border-zinc-300 rounded overflow-hidden bg-white">
                                    @csrf
                                    <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="{{ $item['stock_disponible'] }}" class="w-12 text-center text-xs font-semibold focus:outline-none focus:ring-0 border-none py-1.5" onchange="this.form.submit()">
                                    <button type="submit" class="bg-zinc-50 border-l border-zinc-300 px-2 py-1.5 hover:bg-zinc-100 text-[10px] font-bold text-zinc-600 transition-colors uppercase">
                                        Ok
                                    </button>
                                </form>

                                <!-- Precio Total del Item -->
                                <div class="text-right">
                                    <span class="text-sm font-bold text-zinc-950 block font-sans">
                                        {{ number_format($item['precio'] * $item['cantidad'], 2, ',', '.') }} €
                                    </span>
                                    
                                    <!-- Botón Eliminar -->
                                    <a href="{{ route('carrito.eliminar', $id) }}" class="text-[10px] text-rose-600 hover:text-rose-800 font-bold uppercase tracking-wider block mt-1 transition-colors">
                                        Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen de Pedido (Derecha) -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm sticky top-24">
                        <h2 class="serif-title text-xl font-bold text-zinc-950 pb-4 border-b border-zinc-150">Resumen del Pedido</h2>
                        
                        <div class="space-y-4 py-6 border-b border-zinc-150 text-sm">
                            <!-- Subtotal -->
                            <div class="flex items-center justify-between text-zinc-600">
                                <span>Subtotal</span>
                                <span class="font-semibold text-zinc-950 font-sans">{{ number_format($subtotal, 2, ',', '.') }} €</span>
                            </div>

                            <!-- Costo Envío -->
                            <div class="flex items-center justify-between text-zinc-600">
                                <span>Envío</span>
                                @if($envio == 0)
                                    <span class="font-bold text-emerald-700 uppercase text-xs">Gratis</span>
                                @else
                                    <span class="font-semibold text-zinc-950 font-sans">{{ number_format($envio, 2, ',', '.') }} €</span>
                                @endif
                            </div>

                            <!-- Barra de progreso para Envío Gratis -->
                            @if($subtotal < 400)
                                <div class="bg-amber-50 border border-amber-200 rounded p-3 text-xs text-amber-800">
                                    <p>Añade <strong>{{ number_format(400 - $subtotal, 2, ',', '.') }} €</strong> más para tener <strong>Envío Gratis</strong>.</p>
                                    <div class="w-full bg-amber-200/50 rounded-full h-1.5 mt-2 overflow-hidden">
                                        <div class="bg-amber-800 h-1.5 rounded-full" style="width: {{ ($subtotal / 400) * 100 }}%"></div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-emerald-50 border border-emerald-200 rounded p-3 text-xs text-emerald-800 flex items-center space-x-1.5">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>¡Tu pedido califica para <strong>Envío Gratis</strong>!</span>
                                </div>
                            @endif
                        </div>

                        <!-- Total -->
                        <div class="flex items-center justify-between py-6 text-zinc-950">
                            <span class="text-base font-semibold">Total</span>
                            <span class="text-xl font-bold font-sans">{{ number_format($total, 2, ',', '.') }} €</span>
                        </div>

                        <!-- Checkout Button -->
                        <div class="mt-4">
                            <a href="{{ route('checkout') }}" class="w-full block text-center bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider py-4 rounded transition-colors shadow">
                                Procesar Compra
                            </a>
                            <a href="{{ route('catalogo') }}" class="w-full block text-center border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider py-3.5 rounded mt-3 transition-colors">
                                Seguir Comprando
                            </a>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t border-zinc-150 space-y-3 text-[10px] text-zinc-500 font-semibold uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Pago cifrado seguro SSL</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                <span>Garantía de satisfacción de 14 días</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection
