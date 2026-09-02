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
            
            <!-- Left Column: Full Image & Configured Material Options Below -->
            <div class="flex flex-col space-y-4">
                <!-- Main Image Box (Enhanced framing & presentation) -->
                <div class="relative bg-gradient-to-b from-zinc-50 via-zinc-50/80 to-zinc-100/60 border border-zinc-200/90 rounded-2xl overflow-hidden aspect-square shadow-sm max-h-[520px] flex items-center justify-center p-2 sm:p-3 group cursor-pointer"
                     onclick="openDimensionModal(document.getElementById('main-product-image').src, '{{ $producto->nombre }}')">
                    <!-- Foto 1 (Principal) -->
                    <img id="main-product-image" 
                         src="{{ $producto->imagen_url }}" 
                         alt="{{ $producto->nombre }}" 
                         data-original-src="{{ $producto->imagen_url }}"
                         class="w-full h-full object-contain p-2 transition-all duration-500 ease-out group-hover:scale-105 filter drop-shadow-sm">

                    <!-- Foto 2 (Secundaria en Hover) -->
                    @if($producto->imagen_secundaria_url)
                        <img id="secondary-product-image"
                             src="{{ $producto->imagen_secundaria_url }}"
                             alt="{{ $producto->nombre }} (Secundaria)"
                             class="absolute inset-0 w-full h-full object-contain p-2 sm:p-3 opacity-0 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500 ease-out bg-gradient-to-b from-zinc-50 via-zinc-50/90 to-zinc-100/90 pointer-events-none filter drop-shadow-sm">
                    @endif

                    @if($producto->destacado)
                        <span class="absolute top-4 left-4 bg-amber-850/95 text-white text-[10px] font-extrabold px-3 py-1 uppercase rounded-full tracking-wider shadow-md z-10 backdrop-blur-sm">Destacado</span>
                    @endif

                    <!-- Zoom Overlay Hint -->
                    <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-md border border-zinc-200/80 text-zinc-850 text-xs font-bold px-3 py-1.5 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center space-x-1.5 pointer-events-none">
                        <svg class="w-4 h-4 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                        <span>Clic para ampliar</span>
                    </div>
                </div>

                <!-- Mini Galería de Fotografías del Producto (Foto Principal y Secundaria) -->
                <div class="flex items-center space-x-3 pt-1">
                    <button type="button" 
                            onclick="setMainProductPhoto('{{ $producto->imagen_url }}', this)" 
                            class="photo-thumb-btn border-2 border-amber-800 rounded-xl p-1.5 w-16 h-16 bg-zinc-50 flex items-center justify-center shadow-sm overflow-hidden focus:outline-none transition-all cursor-pointer hover:scale-105"
                            title="Foto Principal">
                        <img src="{{ $producto->imagen_url }}" alt="Foto Principal" class="max-w-full max-h-full object-contain">
                    </button>

                    @if($producto->imagen_secundaria_url)
                        <button type="button" 
                                onclick="setMainProductPhoto('{{ $producto->imagen_secundaria_url }}', this)" 
                                class="photo-thumb-btn border-2 border-zinc-200 hover:border-amber-700 rounded-xl p-1.5 w-16 h-16 bg-zinc-50 flex items-center justify-center shadow-sm overflow-hidden focus:outline-none transition-all cursor-pointer hover:scale-105"
                                title="Foto Secundaria">
                            <img src="{{ $producto->imagen_secundaria_url }}" alt="Foto Secundaria" class="max-w-full max-h-full object-contain">
                        </button>
                    @endif

                    <button type="button" 
                            onclick="openDimensionModal(document.getElementById('main-product-image').src, '{{ $producto->nombre }}')" 
                            class="text-xs font-bold text-amber-850 hover:text-amber-700 flex items-center space-x-1.5 border border-amber-200 bg-amber-50/80 px-3.5 py-2.5 rounded-xl transition-colors cursor-pointer ml-auto">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                        <span>Ampliar Fotografía</span>
                    </button>
                </div>

                <!-- Material Options Selector Configured by Admin -->
                @php
                    $acabados = $producto->acabados_lista;
                    $primerAcabado = $acabados[0]['nombre'] ?? 'Original / Natural';
                @endphp
                <div class="bg-white border border-zinc-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">🪵</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-zinc-700">Acabados y Materiales</span>
                        </div>
                        <span id="selected-color-label" class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200/60 px-2.5 py-0.5 rounded-full">
                            {{ $primerAcabado }}
                        </span>
                    </div>

                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                        @foreach($acabados as $idx => $c)
                            <button type="button" 
                                    id="swatch-btn-{{ $c['key'] }}"
                                    data-nombre="{{ $c['nombre'] }}"
                                    data-mueble-imagen="{{ $c['mueble_imagen'] }}"
                                    onclick="switchProductAcabado('{{ $c['key'] }}')" 
                                    class="color-swatch-btn flex flex-col items-center p-2.5 rounded-xl border-2 {{ $idx === 0 ? 'border-amber-800 bg-amber-50/80 ring-2 ring-amber-800/30' : 'border-zinc-200 bg-white' }} transition-all cursor-pointer hover:shadow-md group">
                                
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg shadow-sm border border-zinc-200 overflow-hidden bg-zinc-100 group-hover:scale-105 transition-transform flex items-center justify-center">
                                    @if(!empty($c['material_imagen']))
                                        <img src="{{ $c['material_imagen'] }}" alt="{{ $c['nombre'] }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-bold text-amber-850">🪵</span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-bold text-zinc-900 mt-1.5 truncate max-w-full text-center leading-tight">{{ $c['nombre'] }}</span>
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

                    <!-- Dimensiones Section (Miniaturas compactas) -->
                    <div class="mt-8 border-t border-zinc-200 pt-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm">📐</span>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-700">Dimensiones del Mueble</h3>
                            </div>
                            <span class="text-[10px] text-zinc-400 font-medium">Clic para ampliar vista</span>
                        </div>

                        <div class="grid grid-cols-3 gap-2.5">
                            <!-- Vista Lateral -->
                            <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-2 text-center flex flex-col items-center justify-between group transition-all hover:border-amber-700 hover:shadow-sm">
                                <span class="text-[10px] font-extrabold text-zinc-600 uppercase tracking-wider block mb-1">Vista Lateral</span>
                                <div class="relative h-16 sm:h-20 w-full rounded-lg overflow-hidden bg-white border border-zinc-150 flex items-center justify-center p-1 cursor-pointer" 
                                     @if($producto->imagen_dimension_lateral_url) onclick="openDimensionModal('{{ $producto->imagen_dimension_lateral_url }}', 'Vista Lateral - Dimensiones')" @endif>
                                    @if($producto->imagen_dimension_lateral_url)
                                        <img src="{{ $producto->imagen_dimension_lateral_url }}" alt="Vista Lateral" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                            <span class="bg-white/95 text-zinc-900 text-[9px] font-bold px-1.5 py-0.5 rounded shadow">🔍 Ampliar</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center text-zinc-300 text-center">
                                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-[9px] font-medium text-zinc-400">Sin plano</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Vista Frontal -->
                            <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-2 text-center flex flex-col items-center justify-between group transition-all hover:border-amber-700 hover:shadow-sm">
                                <span class="text-[10px] font-extrabold text-zinc-600 uppercase tracking-wider block mb-1">Vista Frontal</span>
                                <div class="relative h-16 sm:h-20 w-full rounded-lg overflow-hidden bg-white border border-zinc-150 flex items-center justify-center p-1 cursor-pointer" 
                                     @if($producto->imagen_dimension_frontal_url) onclick="openDimensionModal('{{ $producto->imagen_dimension_frontal_url }}', 'Vista Frontal - Dimensiones')" @endif>
                                    @if($producto->imagen_dimension_frontal_url)
                                        <img src="{{ $producto->imagen_dimension_frontal_url }}" alt="Vista Frontal" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                            <span class="bg-white/95 text-zinc-900 text-[9px] font-bold px-1.5 py-0.5 rounded shadow">🔍 Ampliar</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center text-zinc-300 text-center">
                                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-[9px] font-medium text-zinc-400">Sin plano</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Vista Superior -->
                            <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-2 text-center flex flex-col items-center justify-between group transition-all hover:border-amber-700 hover:shadow-sm">
                                <span class="text-[10px] font-extrabold text-zinc-600 uppercase tracking-wider block mb-1">Vista Superior</span>
                                <div class="relative h-16 sm:h-20 w-full rounded-lg overflow-hidden bg-white border border-zinc-150 flex items-center justify-center p-1 cursor-pointer" 
                                     @if($producto->imagen_dimension_superior_url) onclick="openDimensionModal('{{ $producto->imagen_dimension_superior_url }}', 'Vista Superior - Dimensiones')" @endif>
                                    @if($producto->imagen_dimension_superior_url)
                                        <img src="{{ $producto->imagen_dimension_superior_url }}" alt="Vista Superior" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                            <span class="bg-white/95 text-zinc-900 text-[9px] font-bold px-1.5 py-0.5 rounded shadow">🔍 Ampliar</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center text-zinc-300 text-center">
                                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-[9px] font-medium text-zinc-400">Sin plano</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status Badge -->
                    <div class="mt-6 flex items-center space-x-2">
                        @if($producto->stock > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 mr-1.5"></span>
                                En stock
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
                <div class="mt-8 grid grid-cols-2 gap-4 border-t border-zinc-200 pt-6 text-center text-[10px] text-zinc-500 font-semibold uppercase tracking-wider">
                    <div class="flex flex-col items-center">
                        <svg class="h-5 w-5 text-amber-800 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Garantía de 3 años</span>
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

    <!-- Modal Zoom de Dimensiones -->
    <div id="dimension-modal" class="fixed inset-0 z-[100] hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeDimensionModal()">
        <div class="bg-white rounded-2xl max-w-3xl w-full p-6 shadow-2xl relative" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-zinc-200 pb-4 mb-4">
                <h4 id="dimension-modal-title" class="text-base font-bold text-zinc-900 uppercase tracking-wider">Esquema de Dimensiones</h4>
                <button type="button" onclick="closeDimensionModal()" class="text-zinc-400 hover:text-zinc-700 p-1">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-center max-h-[75vh] overflow-hidden bg-zinc-50 rounded-xl p-3">
                <img id="dimension-modal-img" src="" alt="Plano de Dimensiones" class="max-w-full max-h-[70vh] object-contain">
            </div>
        </div>
    </div>

    <script>
        function setMainProductPhoto(src, btn) {
            const mainImg = document.getElementById('main-product-image');
            if (mainImg) {
                mainImg.style.opacity = '0';
                setTimeout(() => {
                    mainImg.src = src;
                    mainImg.style.opacity = '1';
                }, 150);
            }
            if (btn) {
                document.querySelectorAll('.photo-thumb-btn').forEach(b => {
                    b.classList.remove('border-amber-800');
                    b.classList.add('border-zinc-200');
                });
                btn.classList.remove('border-zinc-200');
                btn.classList.add('border-amber-800');
            }
        }

        function openDimensionModal(imgSrc, title) {
            const modal = document.getElementById('dimension-modal');
            const img = document.getElementById('dimension-modal-img');
            const titleEl = document.getElementById('dimension-modal-title');
            if (modal && img && titleEl) {
                img.src = imgSrc;
                titleEl.textContent = title;
                modal.classList.remove('hidden');
            }
        }

        function closeDimensionModal() {
            const modal = document.getElementById('dimension-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function switchProductAcabado(key) {
            const btn = document.getElementById('swatch-btn-' + key);
            if (!btn) return;

            const acabadoNombre = btn.dataset.nombre;
            const muebleImagen = btn.dataset.muebleImagen;

            const mainImg = document.getElementById('main-product-image');
            const selectedLabel = document.getElementById('selected-color-label');
            const hiddenColorInput = document.getElementById('input-color-seleccionado');

            if (mainImg) {
                const targetSrc = (muebleImagen && muebleImagen.trim() !== '') 
                    ? muebleImagen 
                    : mainImg.dataset.originalSrc;

                mainImg.style.opacity = '0';
                setTimeout(() => {
                    mainImg.src = targetSrc;
                    mainImg.style.opacity = '1';
                }, 150);
            }

            if (selectedLabel) {
                selectedLabel.textContent = acabadoNombre;
            }

            if (hiddenColorInput) {
                hiddenColorInput.value = acabadoNombre;
            }

            // Actualizar apariencia visual de botones de acabado
            document.querySelectorAll('.color-swatch-btn').forEach(b => {
                b.classList.remove('border-amber-800', 'bg-amber-50/80', 'ring-2', 'ring-amber-800/30');
                b.classList.add('border-zinc-200', 'bg-white');
            });

            btn.classList.remove('border-zinc-200', 'bg-white');
            btn.classList.add('border-amber-800', 'bg-amber-50/80', 'ring-2', 'ring-amber-800/30');
        }

        // Retrocompatibilidad
        function switchProductColor(key) {
            switchProductAcabado(key);
        }
    </script>
@endsection
