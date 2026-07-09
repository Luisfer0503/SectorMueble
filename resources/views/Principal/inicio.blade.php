@extends('layouts.app')

@section('titulo', 'Sector Mueble | Muebles de Diseño para tu Hogar')

@section('contenido')
    <!-- Hero Section -->
    <div class="relative bg-zinc-900 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1600" alt="Habitación elegante" class="w-full h-full object-cover opacity-45">
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/70 to-transparent"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40 flex items-center min-h-[600px]">
            <div class="max-w-xl">
                <span class="inline-block text-xs font-semibold tracking-wider text-amber-500 uppercase">Nueva Colección 2026</span>
                <h1 class="serif-title text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mt-4 leading-tight">
                    La belleza de la simplicidad en tu hogar
                </h1>
                <p class="mt-6 text-base sm:text-lg text-zinc-300 leading-relaxed">
                    Descubre nuestra curación exclusiva de muebles minimalistas y duraderos. Piezas concebidas para transformar espacios cotidianos en lugares llenos de armonía.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('catalogo') }}" class="bg-amber-800 hover:bg-amber-700 text-white text-sm font-semibold px-8 py-4 rounded shadow-lg hover:shadow-xl transition-all duration-300">
                        Ver Catálogo completo
                    </a>
                    <a href="#destacados" class="bg-white/10 hover:bg-white/20 text-white text-sm font-semibold px-8 py-4 rounded backdrop-blur-sm border border-white/20 transition-all duration-300">
                        Muebles destacados
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Badges -->
    <div class="bg-white py-12 border-b border-zinc-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-8 gap-x-4 text-center">
                <div class="flex flex-col items-center">
                    <div class="p-3 bg-amber-50 rounded-full text-amber-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-zinc-950">Envío Gratis</h3>
                    <p class="mt-1 text-xs text-zinc-500">Para compras superiores a $8,000 MXN</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="p-3 bg-amber-50 rounded-full text-amber-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-zinc-950">Garantía de 3 Años</h3>
                    <p class="mt-1 text-xs text-zinc-500">Materiales y fabricación certificada</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="p-3 bg-amber-50 rounded-full text-amber-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-zinc-950">Pago 100% Seguro</h3>
                    <p class="mt-1 text-xs text-zinc-500">Encriptación SSL garantizada</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="p-3 bg-amber-50 rounded-full text-amber-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-zinc-950">Devolución de 14 días</h3>
                    <p class="mt-1 text-xs text-zinc-500">¿No te convence? Te devolvemos el dinero</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categorías -->
    <div class="py-20 bg-zinc-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto">
                <h2 class="serif-title text-3xl font-bold text-zinc-950">Inspiración por Estancias</h2>
                <p class="mt-3 text-zinc-500 text-sm">Amuebla cada rincón de tu hogar con colecciones diseñadas para combinar entre sí.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Salón -->
                <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="group relative h-72 rounded overflow-hidden shadow hover:shadow-md transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=600" alt="Salón" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-white text-center">
                        <span class="block text-sm font-semibold uppercase tracking-wider">Salón</span>
                    </div>
                </a>

                <!-- Dormitorio -->
                <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="group relative h-72 rounded overflow-hidden shadow hover:shadow-md transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=600" alt="Dormitorio" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-white text-center">
                        <span class="block text-sm font-semibold uppercase tracking-wider">Dormitorio</span>
                    </div>
                </a>

                <!-- Comedor -->
                <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="group relative h-72 rounded overflow-hidden shadow hover:shadow-md transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1617806118233-18e1db207f62?q=80&w=600" alt="Comedor" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-white text-center">
                        <span class="block text-sm font-semibold uppercase tracking-wider">Comedor</span>
                    </div>
                </a>

                <!-- Oficina -->
                <a href="{{ route('catalogo', ['categoria' => 'Oficina']) }}" class="group relative h-72 rounded overflow-hidden shadow hover:shadow-md transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1505797149-43b0069ec26b?q=80&w=600" alt="Oficina" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-white text-center">
                        <span class="block text-sm font-semibold uppercase tracking-wider">Oficina</span>
                    </div>
                </a>

                <!-- Exterior -->
                <a href="{{ route('catalogo', ['categoria' => 'Exterior']) }}" class="group relative h-72 rounded overflow-hidden shadow hover:shadow-md transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600" alt="Exterior" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-white text-center">
                        <span class="block text-sm font-semibold uppercase tracking-wider">Exterior</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Destacados Section -->
    <div id="destacados" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between border-b border-zinc-100 pb-6">
                <div>
                    <h2 class="serif-title text-3xl font-bold text-zinc-950">Muebles Destacados</h2>
                    <p class="mt-2 text-zinc-500 text-sm">Nuestras piezas más valoradas y demandadas por diseñadores de interiores.</p>
                </div>
                <div>
                    <a href="{{ route('catalogo') }}" class="text-sm font-semibold text-amber-800 hover:text-amber-700 flex items-center space-x-1">
                        <span>Ver todo el catálogo</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($productosDestacados as $producto)
                    <div class="group relative bg-white flex flex-col justify-between h-full">
                        <!-- Img Container -->
                        <div class="relative w-full h-80 rounded bg-zinc-100 overflow-hidden shadow-sm group-hover:shadow transition-shadow">
                            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                @if($producto->tieneDescuento())
                                    <span class="bg-rose-600 text-white text-[10px] font-bold px-2 py-1 uppercase rounded tracking-wider shadow">-{{ $producto->porcentaje_descuento }}% OFERTA</span>
                                @endif
                                <span class="bg-amber-800 text-white text-[10px] font-bold px-2 py-1 uppercase rounded tracking-wider shadow">Destacado</span>
                                @if($producto->stock <= 5)
                                    <span class="bg-rose-600 text-white text-[10px] font-bold px-2 py-1 uppercase rounded tracking-wider shadow">Últimas unidades</span>
                                @endif
                            </div>

                            <!-- Fast Detail View Hover -->
                            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="{{ route('productos.detalle', $producto->id) }}" class="bg-white text-zinc-950 text-xs font-semibold px-4 py-3 rounded shadow hover:bg-amber-800 hover:text-white transition-colors duration-300">
                                    Ver Ficha Técnica
                                </a>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="mt-4 flex-grow flex flex-col justify-between">
                            <div>
                                <span class="text-xs text-zinc-400 font-medium">{{ $producto->categoria }}</span>
                                <h3 class="text-sm font-semibold text-zinc-950 mt-1">
                                    <a href="{{ route('productos.detalle', $producto->id) }}" class="hover:text-amber-800 transition-colors">
                                        {{ $producto->nombre }}
                                    </a>
                                </h3>
                                
                                <!-- Rating -->
                                <div class="flex items-center space-x-1 mt-2">
                                    <span class="text-amber-500 font-semibold text-xs flex items-center">
                                        <svg class="h-3.5 w-3.5 fill-current mr-0.5" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ number_format($producto->calificacion, 1) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Price and Add to Cart -->
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex flex-col">
                                    @if($producto->tieneDescuento())
                                        <span class="text-xs text-zinc-400 line-through font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                        <span class="text-base font-bold text-emerald-700 font-sans">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }} MXN</span>
                                    @else
                                        <span class="text-base font-bold text-zinc-950 font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }} MXN</span>
                                    @endif
                                </div>
                                
                                <form
                                    action="{{ route('carrito.agregar', $producto->id) }}"
                                    method="POST"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-img="{{ $producto->imagen_url }}"
                                    onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                                    @csrf
                                    <button type="submit" class="p-2 bg-zinc-50 hover:bg-amber-800 text-zinc-700 hover:text-white rounded border border-zinc-200 hover:border-transparent transition-colors duration-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

    <!-- Promotional Banner -->
    <div class="relative bg-zinc-950 py-16 sm:py-24">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1618219908412-a29a1bb7b86e?q=80&w=1200" alt="Detalle madera" class="w-full h-full object-cover opacity-20">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="serif-title text-3xl sm:text-4xl font-bold text-white leading-tight">Envíos Especiales y Montaje Profesional</h2>
            <p class="mt-4 text-base text-zinc-300 max-w-xl mx-auto">
                Queremos que amueblar tu casa sea una experiencia placentera. Disfruta de envío gratuito en pedidos mayores a $8,000 MXN y la posibilidad de añadir montaje en casa.
            </p>
            <div class="mt-8">
                <a href="{{ route('catalogo') }}" class="inline-block bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-8 py-3.5 rounded transition-colors shadow-md">
                    Descubrir Catálogo
                </a>
            </div>
        </div>
    </div>
@endsection
