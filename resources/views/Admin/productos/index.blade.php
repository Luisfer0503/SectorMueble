@extends('layouts.admin')

@section('contenido')
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-zinc-200 mb-8 gap-4">
        <div>
            <h1 class="serif-title text-3xl font-bold text-zinc-950">Muebles en el Catálogo</h1>
            <p class="text-zinc-500 text-sm mt-1">Gestiona el catálogo completo de productos visibles en la tienda.</p>
        </div>
        <div>
            <a href="{{ route('admin.productos.crear') }}" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded shadow transition-colors">
                + Agregar Mueble
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-zinc-200 rounded shadow-sm overflow-hidden">
        @if($productos->isEmpty())
            <div class="text-center py-20 text-zinc-500">
                No hay productos en el catálogo actualmente.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-zinc-50 text-xs font-bold text-zinc-450 border-b border-zinc-200 uppercase tracking-wider">
                            <th class="p-4 pl-6">Mueble</th>
                            <th class="p-4">Categoría</th>
                            <th class="p-4">Precio</th>
                            <th class="p-4">Inventario</th>
                            <th class="p-4">Calificación</th>
                            <th class="p-4 text-right pr-6">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($productos as $producto)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <!-- Imagen & Nombre -->
                                <td class="p-4 pl-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded bg-zinc-100 overflow-hidden flex-shrink-0">
                                            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <span class="font-semibold text-zinc-900 block">{{ $producto->nombre }}</span>
                                            @if($producto->destacado)
                                                <span class="inline-block text-[9px] font-bold text-amber-800 bg-amber-50 border border-amber-200 px-1.5 py-0.5 uppercase tracking-wider rounded mt-0.5">Destacado</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Categoría -->
                                <td class="p-4 text-zinc-500 font-medium">{{ $producto->categoria }}</td>
                                
                                <!-- Precio -->
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        @if($producto->tieneDescuento())
                                            <span class="font-semibold text-zinc-400 line-through text-xs font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                            <span class="font-bold text-emerald-700 font-sans">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }}</span>
                                            <span class="text-[9px] font-bold text-white bg-rose-600 rounded px-1.5 py-0.5 w-fit mt-0.5">-{{ $producto->porcentaje_descuento }}%</span>
                                        @else
                                            <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Inventario / Stock -->
                                <td class="p-4">
                                    @if($producto->stock > 5)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 font-sans">
                                            {{ $producto->stock }} disp.
                                        </span>
                                    @elseif($producto->stock > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 font-sans">
                                            {{ $producto->stock }} crít.
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200 font-sans">
                                            Sin Stock
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Calificación -->
                                <td class="p-4">
                                    <span class="text-amber-500 font-bold flex items-center text-xs">
                                        <svg class="h-3.5 w-3.5 fill-current mr-0.5" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ number_format($producto->calificacion, 1) }}
                                    </span>
                                </td>
                                
                                <!-- Acciones -->
                                <td class="p-4 text-right pr-6 space-x-3">
                                    <a href="{{ route('admin.productos.descuento', $producto->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">Descuento</a>
                                    <a href="{{ route('admin.productos.editar', $producto->id) }}" class="text-xs font-bold text-amber-850 hover:underline">Editar</a>
                                    <a href="{{ route('admin.productos.eliminar', $producto->id) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar este mueble del catálogo?')" class="text-xs font-bold text-rose-600 hover:underline">Eliminar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-zinc-200">
                {{ $productos->links() }}
            </div>
        @endif
    </div>
@endsection
