@extends('layouts.app')

@section('titulo', 'Finalizar Compra | Sector Mueble')

@section('contenido')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="serif-title text-3xl font-bold text-zinc-950 mb-8 font-sans">Finalizar Compra</h1>

        <form action="{{ route('checkout.procesar') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Formulario de Envío y Pago (Izquierda - Col 7) -->
                <div class="lg:col-span-7 space-y-8">
                    <!-- Paso 1: Datos de Contacto -->
                    <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                        <div class="flex items-center space-x-3 mb-6">
                            <span class="bg-amber-800 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center text-xs">1</span>
                            <h2 class="text-base font-bold text-zinc-950 uppercase tracking-wider">Datos de Contacto</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nombre_cliente" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Nombre Completo</label>
                                <input type="text" name="nombre_cliente" id="nombre_cliente" required value="{{ old('nombre_cliente') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('nombre_cliente')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="correo_cliente" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Correo Electrónico</label>
                                <input type="email" name="correo_cliente" id="correo_cliente" required value="{{ old('correo_cliente') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('correo_cliente')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="telefono_cliente" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Teléfono móvil</label>
                                <input type="tel" name="telefono_cliente" id="telefono_cliente" required value="{{ old('telefono_cliente') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('telefono_cliente')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Dirección de Envío -->
                    <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                        <div class="flex items-center space-x-3 mb-6">
                            <span class="bg-amber-800 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center text-xs">2</span>
                            <h2 class="text-base font-bold text-zinc-950 uppercase tracking-wider">Dirección de Envío</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-3">
                                <label for="direccion_envio" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Calle, número, piso y puerta</label>
                                <input type="text" name="direccion_envio" id="direccion_envio" required value="{{ old('direccion_envio') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('direccion_envio')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="ciudad" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Ciudad / Localidad</label>
                                <input type="text" name="ciudad" id="ciudad" required value="{{ old('ciudad') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('ciudad')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="codigo_postal" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Código Postal</label>
                                <input type="text" name="codigo_postal" id="codigo_postal" required value="{{ old('codigo_postal') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('codigo_postal')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Datos de Pago (Simulación) -->
                    <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                        <div class="flex items-center space-x-3 mb-6">
                            <span class="bg-amber-800 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center text-xs">3</span>
                            <h2 class="text-base font-bold text-zinc-950 uppercase tracking-wider">Método de Pago</h2>
                        </div>

                        <!-- Tarjeta de Crédito Simulada Visualmente -->
                        <div class="bg-gradient-to-br from-zinc-800 to-zinc-950 text-white p-6 rounded-xl shadow-lg max-w-sm mx-auto mb-6 relative overflow-hidden">
                            <div class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-white/5 pointer-events-none"></div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold tracking-widest text-zinc-400">SECTOR MUEBLE CARD</span>
                                <svg class="h-8 w-8 text-white/80" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/>
                                </svg>
                            </div>
                            
                            <div class="mt-8">
                                <span class="block text-xs font-bold text-zinc-400 uppercase tracking-widest">Número de tarjeta</span>
                                <span class="text-lg font-mono tracking-widest block mt-1">•••• •••• •••• 4242</span>
                            </div>

                            <div class="mt-8 flex justify-between items-center text-xs">
                                <div>
                                    <span class="block text-[10px] text-zinc-400 uppercase">Titular</span>
                                    <span class="font-semibold tracking-wider block mt-0.5">Juan Pérez</span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] text-zinc-400 uppercase">Vence</span>
                                    <span class="font-semibold tracking-wider block mt-0.5">12 / 29</span>
                                </div>
                            </div>
                        </div>

                        <!-- Campos de Entrada -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Nombre en la tarjeta</label>
                                <input type="text" required placeholder="Juan Pérez" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Número de tarjeta</label>
                                <input type="text" required placeholder="4000 1234 5678 9010" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            </div>
                            <div class="grid grid-cols-2 gap-2 sm:col-span-1">
                                <div>
                                    <label class="block text-[10px] font-semibold text-zinc-500 uppercase tracking-wider mb-1">Exp.</label>
                                    <input type="text" required placeholder="MM/AA" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-2 py-2.5 text-center focus:outline-none focus:ring-1 focus:ring-amber-700">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-zinc-500 uppercase tracking-wider mb-1">CVV</label>
                                    <input type="password" required placeholder="•••" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-2 py-2.5 text-center focus:outline-none focus:ring-1 focus:ring-amber-700">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen de Compra (Derecha - Col 5) -->
                <div class="lg:col-span-5">
                    <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm sticky top-24">
                        <h2 class="serif-title text-lg font-bold text-zinc-950 pb-4 border-b border-zinc-150">Resumen del Pedido</h2>
                        
                        <!-- Listado de Artículos -->
                        <div class="divide-y divide-zinc-100 max-h-60 overflow-y-auto py-2">
                            @foreach($carrito as $id => $item)
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-zinc-100 rounded overflow-hidden flex-shrink-0">
                                            <img src="{{ $item['imagen_url'] }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-zinc-900 truncate max-w-[150px]">{{ $item['nombre'] }}</h4>
                                            <span class="text-[10px] text-zinc-400 font-medium">Cant: {{ $item['cantidad'] }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-zinc-950 font-sans">{{ number_format($item['precio'] * $item['cantidad'], 2, ',', '.') }} €</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totales -->
                        <div class="space-y-3 py-4 border-t border-b border-zinc-150 text-xs mt-4">
                            <div class="flex justify-between text-zinc-500">
                                <span>Subtotal</span>
                                <span class="font-semibold text-zinc-900 font-sans">{{ number_format($subtotal, 2, ',', '.') }} €</span>
                            </div>
                            <div class="flex justify-between text-zinc-500">
                                <span>Envío</span>
                                @if($envio == 0)
                                    <span class="font-bold text-emerald-700 uppercase">Gratis</span>
                                @else
                                    <span class="font-semibold text-zinc-900 font-sans">{{ number_format($envio, 2, ',', '.') }} €</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-center py-6 text-zinc-950">
                            <span class="text-sm font-semibold">Total a pagar</span>
                            <span class="text-lg font-bold font-sans">{{ number_format($total, 2, ',', '.') }} €</span>
                        </div>

                        <button type="submit" class="w-full bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider py-4 rounded transition-colors shadow">
                            Confirmar Pedido y Pagar
                        </button>
                        
                        <a href="{{ route('carrito') }}" class="w-full block text-center border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider py-3 mt-3 rounded transition-colors">
                            Volver al Carrito
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
