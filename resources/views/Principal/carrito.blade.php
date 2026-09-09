@extends('layouts.app')

@section('titulo', 'Tu Carrito | Sector Mueble')

@section('contenido')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <h1 class="serif-title text-2xl sm:text-3xl font-bold text-zinc-950 mb-6">Tu Carrito de Compras</h1>

        <!-- Banner Informativo de Carrito Recuperado -->
        @if(auth()->check() && !empty(auth()->user()->carrito_guardado) && !empty($carrito))
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200/80 rounded-2xl shadow-xs flex items-start space-x-3 text-amber-950">
                <div class="p-2 bg-amber-800 text-white rounded-xl shrink-0 mt-0.5 shadow-xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold">¡Tus muebles te están esperando!</h3>
                    <p class="text-xs text-amber-900 mt-0.5">
                        Guardamos automáticamente en tu cuenta la selección de muebles que agregaste previamente.
                    </p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Columna Principal (Izquierda) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Lista de Artículos en Carrito -->
                <div class="bg-white border border-zinc-200/90 rounded-2xl p-4 sm:p-6 shadow-xs">

                    @if(empty($carrito))
                        <!-- Estado Carrito Vacío -->
                        <div class="text-center py-12 px-4">
                            <div class="w-16 h-16 bg-amber-50 text-amber-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-200/60">
                                <svg class="w-8 h-8 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-zinc-900">Tu carrito está vacío</h3>
                            <p class="text-xs text-zinc-500 max-w-xs mx-auto mt-1">Explora nuestro catálogo para añadir muebles exclusivos a tu hogar.</p>
                            <a href="{{ route('catalogo') }}" class="mt-6 inline-block bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold px-8 py-3.5 rounded-2xl transition-all shadow-md">
                                Explorar Muebles
                            </a>
                        </div>
                    @else
                        <!-- Conteo de artículos -->
                        <div class="mb-4 pb-3 border-b border-zinc-100 flex items-center justify-between">
                            <span class="text-xs font-extrabold text-zinc-600 uppercase tracking-wider">
                                {{ array_sum(array_column($carrito, 'cantidad')) }} {{ array_sum(array_column($carrito, 'cantidad')) === 1 ? 'artículo' : 'artículos' }} en tu carrito
                            </span>
                            <a href="{{ route('catalogo') }}" class="text-xs font-bold text-amber-800 hover:text-amber-900 transition-colors">
                                + Agregar más muebles
                            </a>
                        </div>

                        <!-- Artículos del Carrito -->
                        <div class="divide-y divide-zinc-100 space-y-6 divide-y-0">
                            @foreach($carrito as $itemKey => $item)
                                @php
                                    $prodId = $item['producto_id'] ?? (is_numeric($itemKey) ? $itemKey : explode('_', $itemKey)[0]);
                                    $hasNote = !empty($item['solicitud_especial']);
                                @endphp

                                <div class="pt-4 first:pt-0 pb-6 border-b border-zinc-100 last:border-b-0">
                                    <div class="flex items-start gap-4">
                                        
                                        <!-- Imagen del Mueble -->
                                        <div class="w-20 h-20 sm:w-28 sm:h-28 bg-zinc-100 rounded-2xl overflow-hidden shrink-0 border border-zinc-200/80 p-1">
                                            <img src="{{ $item['imagen_url'] }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-cover rounded-xl">
                                        </div>

                                        <!-- Detalles del Producto -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <h3 class="text-sm sm:text-base font-semibold text-zinc-950 leading-snug line-clamp-2">
                                                        <a href="{{ route('productos.detalle', $prodId) }}" class="hover:text-amber-800 transition-colors">
                                                            {{ $item['nombre'] }}
                                                        </a>
                                                    </h3>
                                                    @if(!empty($item['subarticulo_nombre']) || !empty($item['color']))
                                                        <p class="text-xs text-zinc-500 mt-1">
                                                            Acabado: <strong class="text-amber-900 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60">{{ $item['subarticulo_nombre'] ?? $item['color'] }}</strong>
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- Precio del ítem -->
                                                <div class="text-right shrink-0">
                                                    <span class="text-base sm:text-lg font-extrabold text-zinc-950 font-sans block">
                                                        ${{ number_format($item['precio'], 2, '.', ',') }}
                                                    </span>
                                                    @if(!empty($item['con_descuento']) && $item['con_descuento'])
                                                        <span class="text-[10px] text-zinc-400 line-through block">
                                                            ${{ number_format($item['precio_original'], 2, '.', ',') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Fila de Acciones -->
                                            <div class="mt-4 pt-3 border-t border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                
                                                <!-- Lado Izquierdo: Solicitud Especial & Acciones -->
                                                <div class="flex items-center space-x-4 flex-wrap gap-y-2">
                                                    
                                                    <!-- Incluye una solicitud especial -->
                                                    <button type="button" 
                                                        onclick="toggleSolicitudEspecial('note-form-{{ $loop->index }}')" 
                                                        class="inline-flex items-center space-x-1.5 text-xs font-medium text-zinc-700 hover:text-amber-800 transition-colors">
                                                        <svg class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        <span>Incluye una solicitud especial</span>
                                                        <span class="text-amber-800 font-bold underline ml-1">{{ $hasNote ? 'Editar' : 'Agregar' }}</span>
                                                    </button>

                                                    <!-- Separador vertical sutil -->
                                                    <span class="text-zinc-300 hidden sm:inline">|</span>

                                                    <!-- Eliminar -->
                                                    <a href="{{ route('carrito.eliminar', $itemKey) }}" 
                                                       class="text-xs font-semibold text-zinc-600 hover:text-rose-600 underline transition-colors">
                                                        Eliminar
                                                    </a>

                                                    <!-- Guardar para después -->
                                                    <form action="{{ route('carrito.guardar_despues', $itemKey) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-xs font-semibold text-zinc-600 hover:text-amber-800 underline transition-colors">
                                                            Guardar para después
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Lado Derecho: Controles de Cantidad [ - ] 1 [ + ] -->
                                                <div class="flex items-center">
                                                    <form action="{{ route('carrito.actualizar', $itemKey) }}" method="POST" class="inline-flex items-center border border-zinc-300 rounded-full px-1.5 py-0.5 bg-white shadow-2xs">
                                                        @csrf
                                                        <button type="button" 
                                                            onclick="const input = this.form.querySelector('input[name=cantidad]'); if(parseInt(input.value) > 1){ input.value = parseInt(input.value)-1; this.form.submit(); }"
                                                            class="w-7 h-7 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-bold text-sm flex items-center justify-center transition-colors">
                                                            -
                                                        </button>
                                                        <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="{{ $item['stock_disponible'] }}" class="w-8 text-center text-xs font-bold border-none focus:outline-none focus:ring-0 p-0" readonly>
                                                        <button type="button" 
                                                            onclick="const input = this.form.querySelector('input[name=cantidad]'); if(parseInt(input.value) < {{ $item['stock_disponible'] }}){ input.value = parseInt(input.value)+1; this.form.submit(); }"
                                                            class="w-7 h-7 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-bold text-sm flex items-center justify-center transition-colors">
                                                            +
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>

                                            <!-- Formulario Desplegable de Solicitud Especial -->
                                            <div id="note-form-{{ $loop->index }}" class="{{ $hasNote ? '' : 'hidden' }} mt-3 p-3 bg-amber-50/60 border border-amber-200/80 rounded-xl">
                                                <form action="{{ route('carrito.solicitud_especial', $itemKey) }}" method="POST">
                                                    @csrf
                                                    <label class="block text-xs font-bold text-zinc-800 mb-1">Nota o solicitud especial para este mueble:</label>
                                                    <textarea name="solicitud_especial" rows="2" placeholder="Ej: Especificaciones de color, horario de entrega, etc."
                                                        class="w-full bg-white border border-zinc-300 rounded-lg text-xs p-2 text-zinc-800 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700">{{ $item['solicitud_especial'] ?? '' }}</textarea>
                                                    <div class="mt-2 flex items-center justify-end space-x-2">
                                                        <button type="button" onclick="toggleSolicitudEspecial('note-form-{{ $loop->index }}')" class="text-xs text-zinc-500 hover:text-zinc-700 px-3 py-1">Cancelar</button>
                                                        <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold px-4 py-1.5 rounded-lg shadow-xs transition-colors">Guardar Nota</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>

                <!-- Sección de Guardados para después / Mis Favoritos -->
                @if(!empty($favoritos) && count($favoritos) > 0)
                    <div class="bg-gradient-to-r from-amber-50/70 via-orange-50/40 to-amber-50/70 border border-amber-200/90 rounded-2xl p-4 sm:p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base sm:text-lg font-bold text-zinc-950 flex items-center space-x-2">
                                <span>⭐</span>
                                <span>Guardados para después ({{ count($favoritos) }})</span>
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($favoritos as $favKey => $fav)
                                <div class="bg-white border border-zinc-200/90 rounded-2xl p-3.5 flex items-center space-x-3 shadow-2xs">
                                    <img src="{{ $fav['imagen_url'] }}" alt="{{ $fav['nombre'] }}" class="w-16 h-16 rounded-xl object-cover border border-zinc-200 shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-zinc-900 truncate">{{ $fav['nombre'] }}</h4>
                                        <p class="text-xs font-extrabold text-amber-900 font-sans">${{ number_format($fav['precio'], 2, '.', ',') }} MXN</p>
                                        
                                        <div class="mt-2 flex items-center space-x-3 text-[10px]">
                                            <form action="{{ route('carrito.mover_al_carrito', $favKey) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-amber-800 hover:text-amber-900 font-bold underline">
                                                    Mover al carrito
                                                </button>
                                            </form>
                                            <a href="{{ route('carrito.eliminar_guardado', $favKey) }}" class="text-rose-600 hover:text-rose-800 font-bold underline">
                                                Eliminar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Columna Lateral Derecho: Resumen del Pedido -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-zinc-200/90 rounded-2xl p-5 sm:p-6 shadow-sm sticky top-24">
                    
                    <h2 class="serif-title text-base sm:text-xl font-bold text-zinc-950 pb-4 border-b border-zinc-150">Resumen del Pedido</h2>
                    
                    <div class="space-y-3.5 py-4 border-b border-zinc-150 text-xs sm:text-sm">
                        <!-- Subtotal -->
                        <div class="flex items-center justify-between text-zinc-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-zinc-950 font-sans">${{ number_format($subtotal, 2, '.', ',') }}</span>
                        </div>

                        <!-- Costo Envío -->
                        <div class="flex items-center justify-between text-zinc-600">
                            <span>Envío</span>
                            @if($envio == 0)
                                <span class="font-bold text-emerald-700 uppercase text-xs">Gratis</span>
                            @else
                                <span class="font-bold text-zinc-950 font-sans">${{ number_format($envio, 2, '.', ',') }}</span>
                            @endif
                        </div>

                        <!-- Descuento por Cupón (Si aplica) -->
                        @if($cuponAplicado)
                            <div class="flex items-center justify-between text-rose-700 font-medium">
                                <span>Descuento ({{ $cuponAplicado['codigo'] }})</span>
                                <span class="font-sans">-${{ number_format($descuento, 2, '.', ',') }}</span>
                            </div>
                        @endif

                        <!-- Barra de progreso para Envío Gratis -->
                        @if($subtotal < 10000 && $subtotal > 0)
                            <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-3 text-xs text-amber-900 mt-2">
                                <p>Añade <strong>${{ number_format(10000 - $subtotal, 2, '.', ',') }}</strong> más para <strong>Envío Gratis</strong>.</p>
                                <div class="w-full bg-amber-200/60 rounded-full h-1.5 mt-2 overflow-hidden">
                                    <div class="bg-amber-800 h-1.5 rounded-full" style="width: {{ min(100, ($subtotal / 10000) * 100) }}%"></div>
                                </div>
                            </div>
                        @elseif($subtotal >= 10000)
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-xs text-emerald-900 flex items-center space-x-1.5 mt-2">
                                <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>¡Tu pedido califica para <strong>Envío Gratis</strong>!</span>
                            </div>
                        @endif

                        <!-- Sección de Código Promocional / Cupón -->
                        <div class="pt-3 border-t border-zinc-100">
                            @if(!$cuponAplicado)
                                <form action="{{ route('carrito.cupon.aplicar') }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="text" name="codigo" placeholder="CÓDIGO DE CUPÓN" required class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-xs px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 font-mono">
                                    <button type="submit" class="bg-zinc-900 hover:bg-amber-800 text-white text-[10px] font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl transition-colors shrink-0">
                                        Aplicar
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('carrito.cupon.quitar') }}" method="POST" class="flex items-center justify-between text-xs">
                                    @csrf
                                    <span class="text-zinc-700 font-mono text-[11px] bg-zinc-100 px-2.5 py-1 rounded-lg">Cupón: {{ $cuponAplicado['codigo'] }}</span>
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold uppercase tracking-wider text-[10px]">
                                        Quitar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Total Estimado -->
                    <div class="flex items-center justify-between py-4 text-zinc-950">
                        <span class="text-sm sm:text-base font-bold">Total estimado</span>
                        <span class="text-xl sm:text-2xl font-extrabold font-sans text-amber-950">${{ number_format($total, 2, '.', ',') }}</span>
                    </div>

                    <!-- Botón Principal "Continuar" combinando con los colores de la marca -->
                    <div class="mt-2 space-y-3">
                        @if(!empty($carrito))
                            <a href="{{ route('checkout') }}" class="w-full block text-center bg-amber-800 hover:bg-amber-700 text-white text-xs sm:text-sm font-bold uppercase tracking-wider py-4 rounded-2xl transition-all shadow-md hover:shadow-amber-900/30 active:scale-98 border border-white/20">
                                Continuar
                            </a>
                        @else
                            <button disabled class="w-full block text-center bg-zinc-200 text-zinc-400 text-xs sm:text-sm font-bold uppercase tracking-wider py-4 rounded-2xl cursor-not-allowed">
                                Continuar
                            </button>
                        @endif

                        <a href="{{ route('catalogo') }}" class="w-full block text-center border border-zinc-300 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider py-3.5 rounded-2xl transition-colors">
                            Seguir Comprando
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-6 pt-4 border-t border-zinc-150 space-y-2.5 text-[10px] text-zinc-500 font-semibold uppercase tracking-wider">
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
                            <span>Garantía de satisfacción Sector Mueble</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- JavaScript para Toggle de Solicitud Especial -->
    <script>
    function toggleSolicitudEspecial(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('hidden');
        }
    }
    </script>
@endsection
