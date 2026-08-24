@extends('layouts.app')

@section('titulo', $producto->nombre . ' | Sector Mueble')

@section('contenido')
    <!-- Breadcrumbs -->
    <div class="bg-zinc-50 border-b border-zinc-150 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex text-xs font-medium text-zinc-500 space-x-2">
                <a href="{{ route('inicio') }}" class="hover:text-amber-800 transition-colors">Inicio</a>
                <span>/</span>
                <a href="{{ route('catalogo') }}" class="hover:text-amber-800 transition-colors">Catálogo</a>
                <span>/</span>
                <a href="{{ route('catalogo', ['categoria' => $producto->categoria]) }}" class="hover:text-amber-800 transition-colors">{{ $producto->categoria }}</a>
                <span>/</span>
                <span class="text-zinc-800 truncate">{{ $producto->nombre }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Left Column: Full Image & Configured Color Options Below -->
            <div class="flex flex-col space-y-4">
                <!-- Main Image Box (Full view without cropping) -->
                <div class="relative bg-zinc-50/90 border border-zinc-200 rounded-2xl overflow-hidden aspect-square shadow-sm max-h-[520px] flex items-center justify-center p-4 group">
                    <img id="main-product-image" 
                         src="{{ $producto->imagen_url }}" 
                         alt="{{ $producto->nombre }}" 
                         data-original-src="{{ $producto->imagen_url }}"
                         class="max-w-full max-h-full object-contain transition-all duration-500 ease-in-out">
                    
                    <!-- Dynamic Color Tint Overlay -->
                    <div id="color-overlay" 
                         class="absolute inset-0 pointer-events-none transition-opacity duration-500 ease-in-out opacity-0 mix-blend-color rounded-2xl">
                    </div>

                    @if($producto->destacado)
                        <span class="absolute top-4 left-4 bg-amber-800 text-white text-[10px] font-bold px-3 py-1 uppercase rounded tracking-wider shadow z-10">Destacado</span>
                    @endif
                </div>

                <!-- Color Options Selector Configured by Admin -->
                @php
                    $colores = $producto->colores_lista;
                    $primerColor = $colores[0]['nombre'] ?? 'Original / Natural';
                @endphp
                <div class="bg-white border border-zinc-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-wider text-zinc-700">Colores y Acabados</span>
                        </div>
                        <span id="selected-color-label" class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200/60 px-2.5 py-0.5 rounded-full">
                            {{ $primerColor }}
                        </span>
                    </div>

                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                        @foreach($colores as $idx => $c)
                            <button type="button" 
                                    id="swatch-btn-{{ $c['key'] }}"
                                    data-nombre="{{ $c['nombre'] }}"
                                    data-imagen="{{ $c['imagen'] ?? '' }}"
                                    data-hex="{{ $c['hex'] }}"
                                    data-filter="{{ $c['filter'] ?? '' }}"
                                    onclick="switchProductColor('{{ $c['key'] }}')" 
                                    class="color-swatch-btn flex flex-col items-center p-3 rounded-xl border-2 {{ $idx === 0 ? 'border-amber-800 bg-amber-50/80 ring-2 ring-amber-800/30' : 'border-zinc-200 bg-white' }} transition-all cursor-pointer hover:shadow-md group">
                                <span class="w-8 h-8 sm:w-9 sm:h-9 rounded-full shadow border-2 border-white group-hover:scale-110 transition-transform" style="background-color: {{ $c['hex'] }};"></span>
                                <span class="text-xs font-bold text-zinc-900 mt-2 truncate max-w-full text-center">{{ $c['nombre'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Details Info -->
            <div class="flex flex-col justify-between">
                <div>
                    <!-- Category & Title -->
                    <span class="text-xs text-zinc-400 font-bold uppercase tracking-wider">{{ $producto->categoria }}</span>
                    <h1 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950 mt-2 leading-tight">{{ $producto->nombre }}</h1>
                    
                    <!-- Rating and Reviews -->
                    <div class="flex items-center space-x-2 mt-3">
                        <div class="flex items-center text-amber-500 font-semibold text-sm">
                            <svg class="h-4 w-4 fill-current mr-0.5" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>{{ number_format($producto->calificacion, 1) }}</span>
                        </div>
                        <span class="text-zinc-300">|</span>
                        <span class="text-zinc-500 text-xs">(12 valoraciones de clientes)</span>
                    </div>

                    <!-- Price -->
                    <div class="mt-6">
                        @if($producto->tieneDescuento())
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl font-bold text-emerald-700 font-sans">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }} MXN</span>
                                <span class="text-base text-zinc-400 line-through font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                <span class="bg-rose-600 text-white text-xs font-bold px-2.5 py-1 rounded">-{{ $producto->porcentaje_descuento }}%</span>
                            </div>
                            <p class="text-xs text-emerald-600 font-semibold mt-1">
                                Ahorras $ {{ number_format($producto->precio - $producto->precio_descuento, 2, '.', ',') }} MXN
                            </p>
                        @else
                            <span class="text-2xl font-bold text-zinc-950 font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }} MXN</span>
                        @endif
                        <p class="text-xs text-zinc-400 mt-1">IVA incluido. Envío estimado en 3-5 días laborables.</p>
                        <div class="mt-2.5 inline-flex items-center space-x-1.5 text-xs text-amber-900 bg-amber-50 border border-amber-200/80 px-3 py-1 rounded-lg">
                            <svg class="w-3.5 h-3.5 text-amber-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span><strong>Precio de muestra:</strong> Sitio con fines exclusivamente de demostración.</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-8 border-t border-zinc-200 pt-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500">Descripción del Mueble</h3>
                        <p class="mt-3 text-sm text-zinc-600 leading-relaxed">{{ $producto->descripcion }}</p>
                    </div>

                    <!-- Stock Status Badge -->
                    <div class="mt-6 flex items-center space-x-2">
                        @if($producto->stock > 5)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 mr-1.5"></span>
                                En Stock ({{ $producto->stock }} unidades disponibles)
                            </span>
                        @elseif($producto->stock > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-600 mr-1.5"></span>
                                ¡Últimas unidades! Solo quedan {{ $producto->stock }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-800 border border-rose-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-600 mr-1.5"></span>
                                Agotado temporalmente
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Add to Cart Form -->
                <div class="mt-8 border-t border-zinc-200 pt-6">
                    @if($producto->stock > 0)
                        <form
                            action="{{ route('carrito.agregar', $producto->id) }}"
                            method="POST"
                            class="flex flex-row items-center space-x-3"
                            data-nombre="{{ $producto->nombre }}"
                            data-img="{{ $producto->imagen_url }}"
                            onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                            @csrf
                            <input type="hidden" name="color" id="input-color-seleccionado" value="Original / Natural">
                            <!-- Quantity -->
                            <div class="flex items-center border border-zinc-300 rounded-xl overflow-hidden h-12 bg-white flex-shrink-0">
                                <button type="button" onclick="const qty = document.getElementById('cantidad'); if(qty.value > 1) qty.value = parseInt(qty.value)-1;" class="px-3 sm:px-4 py-2 hover:bg-zinc-100 text-zinc-600 font-bold transition-colors text-base">-</button>
                                <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="{{ $producto->stock }}" class="w-10 sm:w-12 text-center text-sm font-semibold border-none focus:outline-none focus:ring-0" readonly>
                                <button type="button" onclick="const qty = document.getElementById('cantidad'); if(qty.value < {{ $producto->stock }}) qty.value = parseInt(qty.value)+1;" class="px-3 sm:px-4 py-2 hover:bg-zinc-100 text-zinc-600 font-bold transition-colors text-base">+</button>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="flex-grow bg-amber-800 hover:bg-amber-700 text-white text-xs sm:text-sm font-bold uppercase tracking-wider h-12 px-4 sm:px-8 rounded-xl transition-colors shadow flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 text-white hidden sm:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Añadir al Carrito</span>
                            </button>
                        </form>

                        <!-- Sticky Bottom Bar Solo para Móviles -->
                        <div class="md:hidden fixed bottom-14 left-0 right-0 z-[70] bg-white/95 backdrop-blur-md border-t border-zinc-200 p-3 shadow-2xl flex items-center justify-between gap-3">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[11px] font-bold text-zinc-900 truncate">{{ $producto->nombre }}</span>
                                <span class="text-xs font-bold text-emerald-700 font-sans">
                                    $ {{ number_format($producto->precio_descuento ?? $producto->precio, 2, '.', ',') }} MXN
                                </span>
                            </div>
                            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" data-nombre="{{ $producto->nombre }}" data-img="{{ $producto->imagen_url }}" onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                                @csrf
                                <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl uppercase tracking-wider shadow whitespace-nowrap">
                                    🛒 Añadir
                                </button>
                            </form>
                        </div>
                    @else
                        <button disabled class="w-full bg-zinc-200 text-zinc-400 text-sm font-bold uppercase tracking-wider py-4 px-8 rounded cursor-not-allowed">
                            Agotado
                        </button>
                    @endif
                </div>

                <!-- Small Trust Features -->
                <div class="mt-8 grid grid-cols-3 gap-4 border-t border-zinc-200 pt-6 text-center text-[10px] text-zinc-500 font-semibold uppercase tracking-wider">
                    <div class="flex flex-col items-center">
                        <svg class="h-5 w-5 text-amber-800 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Garantía de 3 años</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <svg class="h-5 w-5 text-amber-800 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3"/>
                        </svg>
                        <span>Devolución fácil</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <svg class="h-5 w-5 text-amber-800 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>100% Protegido</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Related Products Section -->
        @if(!$productosRelacionados->isEmpty())
            <div class="mt-24 border-t border-zinc-200 pt-16">
                <h2 class="serif-title text-2xl font-bold text-zinc-950">Muebles similares que te pueden gustar</h2>
                
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($productosRelacionados as $rel)
                        <div class="group relative bg-white flex flex-col justify-between h-full border border-zinc-100 rounded overflow-hidden shadow-sm hover:shadow transition-shadow">
                            <!-- Image -->
                            <div class="relative w-full h-64 bg-zinc-100 overflow-hidden">
                                <img src="{{ $rel->imagen_url }}" alt="{{ $rel->nombre }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                    @if($rel->tieneDescuento())
                                        <span class="bg-rose-600 text-white text-[9px] font-bold px-2 py-0.5 uppercase rounded tracking-wider shadow">-{{ $rel->porcentaje_descuento }}% OFERTA</span>
                                    @endif
                                    @if($rel->destacado)
                                        <span class="bg-amber-800 text-white text-[9px] font-bold px-2 py-0.5 uppercase rounded tracking-wider shadow">Destacado</span>
                                    @endif
                                </div>

                                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <a href="{{ route('productos.detalle', $rel->id) }}" class="bg-white text-zinc-950 text-xs font-semibold px-4 py-2 rounded shadow hover:bg-amber-800 hover:text-white transition-colors duration-300">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                            <!-- Info -->
                            <div class="p-4 flex-grow flex flex-col justify-between">
                                <div>
                                    <span class="text-[9px] text-zinc-400 font-bold uppercase tracking-wider">{{ $rel->categoria }}</span>
                                    <h3 class="text-sm font-semibold text-zinc-950 mt-1">
                                        <a href="{{ route('productos.detalle', $rel->id) }}" class="hover:text-amber-800 transition-colors">
                                            {{ $rel->nombre }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex flex-col">
                                        @if($rel->tieneDescuento())
                                            <span class="text-xs text-zinc-400 line-through font-sans">$ {{ number_format($rel->precio, 2, '.', ',') }}</span>
                                            <span class="text-sm font-bold text-emerald-700 font-sans">$ {{ number_format($rel->precio_descuento, 2, '.', ',') }}</span>
                                        @else
                                            <span class="text-sm font-bold text-zinc-950 font-sans">$ {{ number_format($rel->precio, 2, '.', ',') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function switchProductColor(key) {
            const btn = document.getElementById('swatch-btn-' + key);
            if (!btn) return;

            const colorNombre = btn.dataset.nombre;
            const colorImagen = btn.dataset.imagen;
            const colorHex = btn.dataset.hex;
            const colorFilter = btn.dataset.filter;

            const mainImg = document.getElementById('main-product-image');
            const colorOverlay = document.getElementById('color-overlay');
            const selectedLabel = document.getElementById('selected-color-label');
            const hiddenColorInput = document.getElementById('input-color-seleccionado');

            if (mainImg) {
                if (colorImagen && colorImagen.trim() !== '') {
                    // Si el admin configuró una foto específica para este color (el fondo permanece intacto):
                    mainImg.style.opacity = '0';
                    setTimeout(() => {
                        mainImg.src = colorImagen;
                        mainImg.style.filter = 'none';
                        mainImg.style.opacity = '1';
                    }, 150);
                    if (colorOverlay) colorOverlay.style.opacity = '0';
                } else {
                    // Si no tiene foto separada, restaura la foto original
                    if (mainImg.dataset.originalSrc) {
                        mainImg.src = mainImg.dataset.originalSrc;
                    }
                    if (colorFilter && colorFilter !== 'none') {
                        mainImg.style.filter = colorFilter;
                        if (colorOverlay) colorOverlay.style.opacity = '0';
                    } else {
                        mainImg.style.filter = 'none';
                        if (colorOverlay && colorHex) {
                            colorOverlay.style.backgroundColor = colorHex;
                            colorOverlay.style.opacity = '0.35';
                        }
                    }
                }
            }

            if (selectedLabel) {
                selectedLabel.textContent = colorNombre;
            }

            if (hiddenColorInput) {
                hiddenColorInput.value = colorNombre;
            }

            // Actualizar apariencia visual de botones de color
            document.querySelectorAll('.color-swatch-btn').forEach(b => {
                b.classList.remove('border-amber-800', 'bg-amber-50/80', 'ring-2', 'ring-amber-800/30');
                b.classList.add('border-zinc-200', 'bg-white');
            });

            btn.classList.remove('border-zinc-200', 'bg-white');
            btn.classList.add('border-amber-800', 'bg-amber-50/80', 'ring-2', 'ring-amber-800/30');
        }
    </script>
@endsection
