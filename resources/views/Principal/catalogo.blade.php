@extends('layouts.app')

@section('titulo', 'Catálogo de Muebles de Diseño | Sector Mueble')

@section('contenido')
    <!-- Catalog Header -->
    <div class="bg-zinc-100 border-b border-zinc-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950">Catálogo de Muebles</h1>
            <p class="mt-2 text-zinc-500 text-sm">Explora nuestra colección cuidadosamente seleccionada para cada rincón de tu casa.</p>
        </div>
    </div>

    <!-- Main Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Filtros -->
            <div class="lg:col-span-1 bg-white p-6 rounded border border-zinc-200 shadow-sm h-fit">
                <form action="{{ route('catalogo') }}" method="GET" class="space-y-6">
                    <!-- Buscador -->
                    <div>
                        <label for="buscar" class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Buscar</label>
                        <div class="relative">
                            <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" placeholder="Mesa, sofá..." class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div>
                        <label for="categoria" class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Categoría</label>
                        <select name="categoria" id="categoria" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            <option value="todas" {{ request('categoria') == 'todas' || !request('categoria') ? 'selected' : '' }}>Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rango de Precios -->
                    <div>
                        <span class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Precio (€)</span>
                        <div class="flex items-center space-x-2">
                            <input type="number" name="precio_min" value="{{ request('precio_min') }}" placeholder="Mín" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            <span class="text-zinc-400 text-xs">a</span>
                            <input type="number" name="precio_max" value="{{ request('precio_max') }}" placeholder="Máx" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                        </div>
                    </div>

                    <!-- Ordenación -->
                    <div>
                        <label for="ordenar" class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Ordenar por</label>
                        <select name="ordenar" id="ordenar" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            <option value="novedad" {{ request('ordenar') == 'novedad' || !request('ordenar') ? 'selected' : '' }}>Novedades primero</option>
                            <option value="precio_asc" {{ request('ordenar') == 'precio_asc' ? 'selected' : '' }}>Precio: Bajo a Alto</option>
                            <option value="precio_desc" {{ request('ordenar') == 'precio_desc' ? 'selected' : '' }}>Precio: Alto a Bajo</option>
                            <option value="calificacion_desc" {{ request('ordenar') == 'calificacion_desc' ? 'selected' : '' }}>Mejor valorados</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col space-y-2 pt-2">
                        <button type="submit" class="w-full bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded transition-colors shadow">
                            Aplicar Filtros
                        </button>
                        @if(request()->anyFilled(['buscar', 'categoria', 'precio_min', 'precio_max', 'ordenar']))
                            <a href="{{ route('catalogo') }}" class="w-full text-center border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider py-3 rounded transition-colors">
                                Limpiar Filtros
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Grid de Productos -->
            <div class="lg:col-span-3">
                @if($productos->isEmpty())
                    <div class="text-center py-20 bg-white border border-zinc-200 rounded p-8">
                        <svg class="h-12 w-12 text-zinc-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-zinc-950">No encontramos ningún mueble</h3>
                        <p class="mt-2 text-zinc-500 text-sm">Prueba ajustando los filtros de búsqueda o categoría.</p>
                        <a href="{{ route('catalogo') }}" class="mt-6 inline-block bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded transition-colors">
                            Ver todo el catálogo
                        </a>
                    </div>
                @else
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($productos as $producto)
                            <div class="group relative bg-white flex flex-col justify-between h-full border border-zinc-100 rounded overflow-hidden shadow-sm hover:shadow transition-all duration-300">
                                <!-- Imagen -->
                                <div class="relative w-full h-72 bg-zinc-100 overflow-hidden">
                                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                        @if($producto->destacado)
                                            <span class="bg-amber-800 text-white text-[9px] font-bold px-2 py-0.5 uppercase rounded tracking-wider shadow">Destacado</span>
                                        @endif
                                        @if($producto->stock <= 5)
                                            <span class="bg-rose-600 text-white text-[9px] font-bold px-2 py-0.5 uppercase rounded tracking-wider shadow">Últimas unidades</span>
                                        @endif
                                    </div>

                                    <!-- Hover overlay -->
                                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <a href="{{ route('productos.detalle', $producto->id) }}" class="bg-white text-zinc-950 text-xs font-semibold px-4 py-2.5 rounded shadow hover:bg-amber-800 hover:text-white transition-colors duration-300">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>

                                <!-- Contenido Info -->
                                <div class="p-4 flex-grow flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">{{ $producto->categoria }}</span>
                                        <h3 class="text-sm font-semibold text-zinc-950 mt-1">
                                            <a href="{{ route('productos.detalle', $producto->id) }}" class="hover:text-amber-800 transition-colors">
                                                {{ $producto->nombre }}
                                            </a>
                                        </h3>

                                        <!-- Rating -->
                                        <div class="flex items-center space-x-1 mt-1.5">
                                            <span class="text-amber-500 font-semibold text-xs flex items-center">
                                                <svg class="h-3 w-3 fill-current mr-0.5" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                {{ number_format($producto->calificacion, 1) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Precio y Carrito -->
                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-zinc-100">
                                        <span class="text-sm font-bold text-zinc-950 font-sans">{{ number_format($producto->precio, 2, ',', '.') }} €</span>
                                        
                                        <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs bg-zinc-50 hover:bg-amber-800 text-zinc-700 hover:text-white rounded border border-zinc-200 hover:border-transparent px-3 py-1.5 flex items-center space-x-1 transition-all duration-300">
                                                <span>Añadir</span>
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-12">
                        {{ $productos->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
