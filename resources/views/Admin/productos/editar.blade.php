@extends('layouts.admin')

@section('contenido')
    <!-- Header -->
    <div class="pb-6 border-b border-zinc-200 mb-8">
        <nav class="flex text-xs font-medium text-zinc-500 space-x-2 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-850">Inicio</a>
            <span>/</span>
            <a href="{{ route('admin.productos') }}" class="hover:text-amber-850">Muebles</a>
            <span>/</span>
            <span class="text-zinc-800">Editar</span>
        </nav>
        <h1 class="serif-title text-3xl font-bold text-zinc-950">Editar Mueble</h1>
        <p class="text-zinc-500 text-sm mt-1">Modifica los detalles del mueble "{{ $producto->nombre }}".</p>
    </div>

    <!-- Edit Form -->
    <div class="bg-white border border-zinc-200 rounded p-6 sm:p-8 shadow-sm max-w-3xl">
        <form action="{{ route('admin.productos.actualizar', $producto->id) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="sm:col-span-2">
                    <label for="nombre" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Nombre del Mueble</label>
                    <input type="text" name="nombre" id="nombre" required value="{{ old('nombre', $producto->nombre) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('nombre')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Categoría</label>
                    <select name="categoria" id="categoria" required class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                        <option value="Salón" {{ old('categoria', $producto->categoria) === 'Salón' ? 'selected' : '' }}>Salón</option>
                        <option value="Dormitorio" {{ old('categoria', $producto->categoria) === 'Dormitorio' ? 'selected' : '' }}>Dormitorio</option>
                        <option value="Comedor" {{ old('categoria', $producto->categoria) === 'Comedor' ? 'selected' : '' }}>Comedor</option>
                        <option value="Oficina" {{ old('categoria', $producto->categoria) === 'Oficina' ? 'selected' : '' }}>Oficina</option>
                        <option value="Exterior" {{ old('categoria', $producto->categoria) === 'Exterior' ? 'selected' : '' }}>Exterior</option>
                    </select>
                    @error('categoria')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label for="precio" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Precio ($ MXN)</label>
                    <input type="number" step="0.01" name="precio" id="precio" required value="{{ old('precio', $producto->precio) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('precio')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Stock / Inventario -->
                <div>
                    <label for="stock" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Inventario (Stock)</label>
                    <input type="number" name="stock" id="stock" required value="{{ old('stock', $producto->stock) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('stock')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Calificación Inicial -->
                <div>
                    <label for="calificacion" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Calificación (1 a 5)</label>
                    <input type="number" step="0.1" min="1" max="5" name="calificacion" id="calificacion" required value="{{ old('calificacion', $producto->calificacion) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('calificacion')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- URL Imagen -->
                <div class="sm:col-span-2">
                    <label for="imagen_url" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">URL de la Imagen</label>
                    <input type="url" name="imagen_url" id="imagen_url" required value="{{ old('imagen_url', $producto->imagen_url) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('imagen_url')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripcion -->
                <div class="sm:col-span-2">
                    <label for="descripcion" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Descripción del Mueble</label>
                    <textarea name="descripcion" id="descripcion" required rows="4" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Destacado -->
                <div class="sm:col-span-2 flex items-center">
                    <input type="checkbox" name="destacado" id="destacado" {{ old('destacado', $producto->destacado) ? 'checked' : '' }} class="h-4 w-4 text-amber-850 border-zinc-300 rounded focus:ring-amber-700">
                    <label for="destacado" class="ml-2 text-xs text-zinc-700 font-medium cursor-pointer">Destacar este mueble en la página principal</label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-zinc-150 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.productos') }}" class="border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider px-6 py-3.5 rounded transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3.5 rounded shadow transition-colors">
                    Actualizar Mueble
                </button>
            </div>
        </form>
    </div>
@endsection
