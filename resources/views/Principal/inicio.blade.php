@extends('layouts.app')

@section('titulo', 'Sector Mueble | Muebles de Diseño para tu Hogar')

@section('contenido')
    <!-- Hero Section de Alta Gama -->
    <div class="relative bg-zinc-950 overflow-hidden min-h-[620px] lg:min-h-[720px] flex items-center">
        <!-- Fondo con Imagen Curada y Degradado Gradual -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1800" alt="Mueble de diseño contemporáneo" class="w-full h-full object-cover opacity-35 scale-105 transition-transform duration-1000">
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/80 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-36">
            <div class="max-w-2xl">
                <!-- Badge Flotante con Glassmorphism -->
                <div class="inline-flex items-center space-x-2.5 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold uppercase tracking-widest text-amber-400 shadow-lg">
                    <span>✨ Nueva Colección 2026</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                </div>

                <!-- Titular de Impacto -->
                <h1 class="serif-title text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-white mt-6 leading-[1.1]">
                    La belleza de la simplicidad <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-amber-400 to-amber-500">en tu hogar</span>
                </h1>

                <!-- Subtítulo Descriptor -->
                <p class="mt-6 text-base sm:text-lg lg:text-xl text-zinc-300 font-light leading-relaxed">
                    Descubre nuestra colección exclusiva de muebles minimalistas, fabricados con maderas sustentables y diseñados para transformar tus espacios cotidianos en santuarios de armonía.
                </p>

                <!-- Insignias rápidas -->
                <div class="mt-6 flex flex-wrap gap-4 text-xs font-semibold text-zinc-300">
                    <span class="flex items-center space-x-1.5 bg-zinc-900/80 px-3 py-1.5 rounded-lg border border-zinc-800">
                        <span class="text-amber-400">🌱</span> <span>Madera Sustentable</span>
                    </span>
                    <span class="flex items-center space-x-1.5 bg-zinc-900/80 px-3 py-1.5 rounded-lg border border-zinc-800">
                        <span class="text-amber-400">📦</span> <span>Envío Gratis &gt; $8,000 MXN</span>
                    </span>
                    <span class="flex items-center space-x-1.5 bg-zinc-900/80 px-3 py-1.5 rounded-lg border border-zinc-800">
                        <span class="text-amber-400">🛡️</span> <span>Garantía de 3 Años</span>
                    </span>
                </div>

                <!-- Botones de Acción (CTA) -->
                <div class="mt-10 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalogo') }}" class="group relative inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-amber-700 via-amber-800 to-amber-900 hover:from-amber-600 hover:to-amber-800 text-white text-sm font-bold px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <span>Explorar Catálogo</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#destacados" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 text-white text-sm font-bold px-8 py-4 rounded-2xl backdrop-blur-md border border-white/20 transition-all duration-300">
                        Muebles Destacados
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Badges de Confianza con Tarjetas de Cristal -->
    <div class="bg-gradient-to-b from-white to-[#FAF3E0]/40 py-12 sm:py-16 border-b border-amber-900/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="hover-lift bg-white/90 backdrop-blur-sm p-6 rounded-2xl border border-amber-900/10 shadow-sm flex items-start space-x-4">
                    <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-200/80 rounded-2xl text-amber-900 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Envío Gratuito</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">Sin costo extra en compras mayores a $8,000 MXN</p>
                    </div>
                </div>

                <div class="hover-lift bg-white/90 backdrop-blur-sm p-6 rounded-2xl border border-amber-900/10 shadow-sm flex items-start space-x-4">
                    <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-200/80 rounded-2xl text-amber-900 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Garantía de 3 Años</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">Protección total en estructura y acabados</p>
                    </div>
                </div>

                <div class="hover-lift bg-white/90 backdrop-blur-sm p-6 rounded-2xl border border-amber-900/10 shadow-sm flex items-start space-x-4">
                    <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-200/80 rounded-2xl text-amber-900 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Pago 100% Seguro</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">Encriptación bancaria de alta seguridad</p>
                    </div>
                </div>

                <div class="hover-lift bg-white/90 backdrop-blur-sm p-6 rounded-2xl border border-amber-900/10 shadow-sm flex items-start space-x-4">
                    <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-200/80 rounded-2xl text-amber-900 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Devolución en 14 Días</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">Satisfacción garantizada o tu dinero de vuelta</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Categorías "Inspiración por Estancias" -->
    <div class="py-20 sm:py-28 bg-[#FAF3E0]/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-800 bg-amber-100/80 border border-amber-200/80 px-3.5 py-1 rounded-full">Espacios Inspiradores</span>
                <h2 class="serif-title text-3xl sm:text-5xl font-bold text-zinc-950 mt-3">Inspiración por Estancias</h2>
                <p class="mt-3 text-zinc-600 text-sm sm:text-base">Amuebla cada ambiente con piezas concebidas para coordinar perfectamente entre sí.</p>
            </div>

            <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Salón -->
                <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                    <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=700" alt="Salón" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white flex flex-col justify-end">
                        <span class="text-[11px] font-bold text-amber-300 uppercase tracking-widest">Colección Estancia</span>
                        <h3 class="text-xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Salón</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1 transition-transform">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Dormitorio -->
                <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                    <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=700" alt="Dormitorio" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white flex flex-col justify-end">
                        <span class="text-[11px] font-bold text-amber-300 uppercase tracking-widest">Colección Estancia</span>
                        <h3 class="text-xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Recámara</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1 transition-transform">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Comedor -->
                <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                    <img src="https://images.unsplash.com/photo-1617806118233-18e1db207f62?q=80&w=700" alt="Comedor" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white flex flex-col justify-end">
                        <span class="text-[11px] font-bold text-amber-300 uppercase tracking-widest">Colección Estancia</span>
                        <h3 class="text-xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Comedor</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1 transition-transform">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Oficina -->
                <a href="{{ route('catalogo', ['categoria' => 'Oficina']) }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                    <img src="https://images.unsplash.com/photo-1505797149-43b0069ec26b?q=80&w=700" alt="Oficina" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white flex flex-col justify-end">
                        <span class="text-[11px] font-bold text-amber-300 uppercase tracking-widest">Colección Estancia</span>
                        <h3 class="text-xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Oficina</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1 transition-transform">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Exterior -->
                <a href="{{ route('catalogo', ['categoria' => 'Exterior']) }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                    <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=700" alt="Exterior" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white flex flex-col justify-end">
                        <span class="text-[11px] font-bold text-amber-300 uppercase tracking-widest">Colección Estancia</span>
                        <h3 class="text-xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Exterior</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1 transition-transform">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </div>

    <!-- Seccion Destacados -->
    <div id="destacados" class="py-20 sm:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-zinc-150 pb-6 gap-4">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-amber-800">Diseños Exclusivos</span>
                    <h2 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950 mt-1">Muebles Destacados</h2>
                    <p class="mt-2 text-zinc-500 text-sm">Nuestras piezas más aclamadas por arquitectos e interioristas.</p>
                </div>
                <div>
                    <a href="{{ route('catalogo') }}" class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-amber-800 hover:text-amber-950 bg-amber-50 hover:bg-amber-100 px-4 py-2.5 rounded-xl border border-amber-200/70 transition-all">
                        <span>Ver catálogo completo</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Products Grid con Tarjetas Rediseñadas -->
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($productosDestacados as $producto)
                    <div class="group relative bg-white border border-zinc-200/90 rounded-3xl p-3 flex flex-col justify-between h-full hover:border-amber-700/40 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1.5">
                        
                        <!-- Img Container con Aspecto Proporcional -->
                        <div class="relative w-full h-72 sm:h-80 rounded-2xl bg-zinc-100 overflow-hidden">
                            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            
                            <!-- Insignias / Badges -->
                            <div class="absolute top-3 left-3 flex flex-col space-y-1.5 z-10">
                                @if($producto->tieneDescuento())
                                    <span class="bg-rose-600 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">-{{ $producto->porcentaje_descuento }}% OFERTA</span>
                                @endif
                                <span class="bg-amber-800 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">Destacado</span>
                                @if($producto->stock <= 5)
                                    <span class="bg-zinc-900 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">Últimas {{ $producto->stock }} unid.</span>
                                @endif
                            </div>

                            <!-- Botón de Vista Rápida en Hover -->
                            <div class="absolute inset-0 bg-zinc-950/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                <a href="{{ route('productos.detalle', $producto->id) }}" class="bg-white/95 text-zinc-950 text-xs font-bold px-5 py-3 rounded-xl shadow-xl hover:bg-amber-800 hover:text-white transition-all transform group-hover:scale-100 scale-95">
                                    Ver Ficha Técnica
                                </a>
                            </div>
                        </div>

                        <!-- Información del producto -->
                        <div class="p-3 flex-grow flex flex-col justify-between mt-2">
                            <div>
                                <span class="text-[11px] font-bold text-amber-800 uppercase tracking-widest">{{ $producto->categoria }}</span>
                                <h3 class="text-base font-bold text-zinc-950 mt-1 line-clamp-1">
                                    <a href="{{ route('productos.detalle', $producto->id) }}" class="hover:text-amber-800 transition-colors">
                                        {{ $producto->nombre }}
                                    </a>
                                </h3>
                                
                                <!-- Calificación -->
                                <div class="flex items-center space-x-1.5 mt-2">
                                    <div class="flex text-amber-500 text-xs">
                                        <svg class="h-4 w-4 fill-current text-amber-500" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-bold text-zinc-800">{{ number_format($producto->calificacion, 1) }}</span>
                                    <span class="text-xs text-zinc-400 font-medium">(Cliente verificado)</span>
                                </div>
                            </div>
                            
                            <!-- Precio y Botón de Carrito -->
                            <div class="flex items-center justify-between mt-5 pt-3 border-t border-zinc-100">
                                <div class="flex flex-col">
                                    @if($producto->tieneDescuento())
                                        <span class="text-xs text-zinc-400 line-through font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                        <span class="text-lg font-extrabold text-amber-900 font-sans">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }} <span class="text-xs font-normal text-zinc-500">MXN</span></span>
                                    @else
                                        <span class="text-lg font-extrabold text-zinc-950 font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }} <span class="text-xs font-normal text-zinc-500">MXN</span></span>
                                    @endif
                                </div>
                                
                                <form
                                    action="{{ route('carrito.agregar', $producto->id) }}"
                                    method="POST"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-img="{{ $producto->imagen_url }}"
                                    onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                                    @csrf
                                    <button type="submit" aria-label="Añadir {{ $producto->nombre }} al carrito" class="p-3 bg-amber-50 hover:bg-amber-800 text-amber-900 hover:text-white rounded-2xl border border-amber-200/80 hover:border-transparent transition-all duration-300 shadow-sm hover:shadow-md">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Banner Promocional de Experiencia de Compra -->
    <div class="relative bg-zinc-950 py-20 sm:py-28 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1618219908412-a29a1bb7b86e?q=80&w=1400" alt="Detalle artesanal de madera" class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/90 to-zinc-950/60"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-400 bg-white/10 border border-white/15 px-3.5 py-1.5 rounded-full">Servicio Premium</span>
                <h2 class="serif-title text-3xl sm:text-5xl font-bold text-white mt-4 leading-tight">Envíos Especiales y Montaje Profesional</h2>
                <p class="mt-4 text-base sm:text-lg text-zinc-300 leading-relaxed font-light">
                    Transforma tu casa sin preocupaciones. Contamos con transporte especializado, empaque protector multicapa y la opción de ensamble profesional en la comodidad de tu hogar.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('catalogo') }}" class="inline-flex items-center space-x-2 bg-amber-700 hover:bg-amber-600 text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-2xl transition-all shadow-xl hover:shadow-2xl">
                        <span>Descubrir Catálogo</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

