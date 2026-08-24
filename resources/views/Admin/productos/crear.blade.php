@extends('layouts.admin')

@section('contenido')
    <!-- Header -->
    <div class="pb-6 border-b border-zinc-200 mb-8">
        <nav class="flex text-xs font-medium text-zinc-500 space-x-2 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-850">Inicio</a>
            <span>/</span>
            <a href="{{ route('admin.productos') }}" class="hover:text-amber-850">Muebles</a>
            <span>/</span>
            <span class="text-zinc-800">Agregar</span>
        </nav>
        <h1 class="serif-title text-3xl font-bold text-zinc-950">Agregar Nuevo Mueble</h1>
        <p class="text-zinc-500 text-sm mt-1">Completa los campos para dar de alta una pieza en la tienda.</p>
    </div>

    <!-- Creation Form -->
    <div class="bg-white border border-zinc-200 rounded p-6 sm:p-8 shadow-sm max-w-3xl">
        <form action="{{ route('admin.productos.guardar') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="sm:col-span-2">
                    <label for="nombre" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Nombre del Mueble</label>
                    <input type="text" name="nombre" id="nombre" required value="{{ old('nombre') }}" placeholder="Ej: Sofá modular escandinavo" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('nombre')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Categoría</label>
                    <select name="categoria" id="categoria" required class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                        <option value="" disabled selected>Selecciona una categoría</option>
                        <option value="Salón" {{ old('categoria') === 'Salón' ? 'selected' : '' }}>Salón</option>
                        <option value="Dormitorio" {{ old('categoria') === 'Dormitorio' ? 'selected' : '' }}>Dormitorio</option>
                        <option value="Comedor" {{ old('categoria') === 'Comedor' ? 'selected' : '' }}>Comedor</option>
                        <option value="Sillas y Bancos" {{ old('categoria') === 'Sillas y Bancos' ? 'selected' : '' }}>Sillas y Bancos</option>
                        <option value="Muebles Auxiliares" {{ old('categoria') === 'Muebles Auxiliares' ? 'selected' : '' }}>Muebles Auxiliares</option>
                        <option value="Oficina" {{ old('categoria') === 'Oficina' ? 'selected' : '' }}>Oficina</option>
                        <option value="Exterior" {{ old('categoria') === 'Exterior' ? 'selected' : '' }}>Exterior</option>
                    </select>
                    @error('categoria')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label for="precio" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Precio ($ MXN)</label>
                    <input type="number" step="0.01" name="precio" id="precio" required value="{{ old('precio') }}" placeholder="Ej: 4500.00" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('precio')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Stock / Inventario -->
                <div>
                    <label for="stock" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Inventario (Stock)</label>
                    <input type="number" name="stock" id="stock" required value="{{ old('stock', 10) }}" placeholder="Ej: 15" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('stock')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Calificación Inicial -->
                <div>
                    <label for="calificacion" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Calificación Inicial (1 a 5)</label>
                    <input type="number" step="0.1" min="1" max="5" name="calificacion" id="calificacion" required value="{{ old('calificacion', 5.0) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('calificacion')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Subir Imagen desde el Equipo -->
                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Imagen del Mueble *</label>
                    
                    <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-lg p-6 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_archivo').click()">
                        <input type="file" name="imagen_archivo" id="imagen_archivo" accept="image/*" required class="hidden" onchange="previewImage(this)">
                        
                        <div id="upload-placeholder" class="space-y-2">
                            <svg class="mx-auto h-10 w-10 text-amber-800/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="text-sm font-semibold text-zinc-800">
                                Selecciona una imagen desde tu equipo
                            </div>
                            <p class="text-xs text-zinc-500">Formatos soportados: JPG, PNG, WEBP, GIF (Máximo 5MB)</p>
                        </div>

                        <!-- Previsualización de imagen -->
                        <div id="image-preview-container" class="hidden flex-col items-center">
                            <img id="image-preview" src="#" alt="Previsualización" class="max-h-56 rounded border border-zinc-200 shadow-sm object-cover mb-2">
                            <span class="text-xs text-amber-850 font-semibold hover:underline">Haz clic si deseas cambiar la imagen elegida</span>
                        </div>
                    </div>

                    @error('imagen_archivo')
                        <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripcion -->
                <div class="sm:col-span-2">
                    <label for="descripcion" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Descripción del Mueble</label>
                    <textarea name="descripcion" id="descripcion" required rows="4" placeholder="Escribe las características principales del mueble, materiales, dimensiones..." class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Destacado -->
                <div class="sm:col-span-2 flex items-center">
                    <input type="checkbox" name="destacado" id="destacado" {{ old('destacado') ? 'checked' : '' }} class="h-4 w-4 text-amber-850 border-zinc-300 rounded focus:ring-amber-700">
                    <label for="destacado" class="ml-2 text-xs text-zinc-700 font-medium cursor-pointer">Destacar este mueble en la página principal</label>
                </div>

                <!-- Gestión de Colores y Variantes del Mueble -->
                <div class="sm:col-span-2 border-t border-zinc-200 pt-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-800">
                                🎨 Acabados y Colores del Mueble
                            </label>
                            <p class="text-xs text-zinc-500 mt-0.5">
                                Agrega los colores disponibles (ej: Original, Nogal Oscuro, Gris Grafito...). Puedes subir opcionalmente la foto para cada color.
                            </p>
                        </div>
                        <button type="button" onclick="agregarColorRow()" class="bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold px-3 py-1.5 rounded transition-colors flex items-center space-x-1 cursor-pointer">
                            <span>+ Agregar otro color</span>
                        </button>
                    </div>

                    <div id="colores-container" class="space-y-3">
                        <!-- Color 1 Default -->
                        <div class="color-row flex flex-col sm:flex-row items-center gap-3 p-3 bg-zinc-50 border border-zinc-200 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <input type="color" name="colores_hex[]" value="#D4A373" class="h-9 w-12 p-0.5 border border-zinc-300 rounded cursor-pointer" title="Selecciona tono HEX">
                            </div>
                            <div class="flex-grow w-full sm:w-auto">
                                <input type="text" name="colores_nombres[]" required value="Original / Natural" placeholder="Nombre del color" class="w-full bg-white border border-zinc-200 rounded text-xs px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            </div>
                            <div class="w-full sm:w-auto flex items-center space-x-2">
                                <input type="file" name="colores_imagenes[0]" accept="image/*" class="text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100">
                            </div>
                            <button type="button" onclick="eliminarColorRow(this)" class="text-rose-600 hover:text-rose-800 p-1 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Color 2 Default -->
                        <div class="color-row flex flex-col sm:flex-row items-center gap-3 p-3 bg-zinc-50 border border-zinc-200 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <input type="color" name="colores_hex[]" value="#4A2810" class="h-9 w-12 p-0.5 border border-zinc-300 rounded cursor-pointer" title="Selecciona tono HEX">
                            </div>
                            <div class="flex-grow w-full sm:w-auto">
                                <input type="text" name="colores_nombres[]" required value="Nogal Oscuro" placeholder="Nombre del color" class="w-full bg-white border border-zinc-200 rounded text-xs px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            </div>
                            <div class="w-full sm:w-auto flex items-center space-x-2">
                                <input type="file" name="colores_imagenes[1]" accept="image/*" class="text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100">
                            </div>
                            <button type="button" onclick="eliminarColorRow(this)" class="text-rose-600 hover:text-rose-800 p-1 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Color 3 Default -->
                        <div class="color-row flex flex-col sm:flex-row items-center gap-3 p-3 bg-zinc-50 border border-zinc-200 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <input type="color" name="colores_hex[]" value="#374151" class="h-9 w-12 p-0.5 border border-zinc-300 rounded cursor-pointer" title="Selecciona tono HEX">
                            </div>
                            <div class="flex-grow w-full sm:w-auto">
                                <input type="text" name="colores_nombres[]" required value="Gris Grafito" placeholder="Nombre del color" class="w-full bg-white border border-zinc-200 rounded text-xs px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            </div>
                            <div class="w-full sm:w-auto flex items-center space-x-2">
                                <input type="file" name="colores_imagenes[2]" accept="image/*" class="text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100">
                            </div>
                            <button type="button" onclick="eliminarColorRow(this)" class="text-rose-600 hover:text-rose-800 p-1 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-zinc-150 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.productos') }}" class="border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider px-6 py-3.5 rounded transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3.5 rounded shadow transition-colors">
                    Guardar Mueble
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    previewContainer.classList.add('flex');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        let colorIndexCount = 3;
        function agregarColorRow() {
            const container = document.getElementById('colores-container');
            const row = document.createElement('div');
            row.className = 'color-row flex flex-col sm:flex-row items-center gap-3 p-3 bg-zinc-50 border border-zinc-200 rounded-lg';
            row.innerHTML = `
                <div class="flex items-center space-x-2">
                    <input type="color" name="colores_hex[]" value="#4B5320" class="h-9 w-12 p-0.5 border border-zinc-300 rounded cursor-pointer" title="Selecciona tono HEX">
                </div>
                <div class="flex-grow w-full sm:w-auto">
                    <input type="text" name="colores_nombres[]" required placeholder="Nombre del color (ej: Verde Olivo, Azul Marino...)" class="w-full bg-white border border-zinc-200 rounded text-xs px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                </div>
                <div class="w-full sm:w-auto flex items-center space-x-2">
                    <input type="file" name="colores_imagenes[${colorIndexCount}]" accept="image/*" class="text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100">
                </div>
                <button type="button" onclick="eliminarColorRow(this)" class="text-rose-600 hover:text-rose-800 p-1 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            `;
            container.appendChild(row);
            colorIndexCount++;
        }

        function eliminarColorRow(btn) {
            const rows = document.querySelectorAll('.color-row');
            if (rows.length > 1) {
                btn.closest('.color-row').remove();
            } else {
                alert('Debes mantener al menos 1 color disponible para el mueble.');
            }
        }
    </script>
@endsection
