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
        <form action="{{ route('admin.productos.actualizar', $producto->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                        <option value="Sillas y Bancos" {{ old('categoria', $producto->categoria) === 'Sillas y Bancos' ? 'selected' : '' }}>Sillas y Bancos</option>
                        <option value="Muebles Auxiliares" {{ old('categoria', $producto->categoria) === 'Muebles Auxiliares' ? 'selected' : '' }}>Muebles Auxiliares</option>
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

                <!-- Cambiar/Actualizar Imágenes (Foto 1 Principal & Foto 2 Secundaria) -->
                <div class="sm:col-span-2 space-y-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-800">
                        📸 Fotografías del Mueble (Foto 1 Principal y Foto 2 Secundaria)
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Foto 1 (Principal) -->
                        <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-xl p-4 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_archivo').click()">
                            <span class="block text-xs font-extrabold uppercase tracking-wider text-amber-900 mb-2">Foto 1 (Principal)</span>
                            <input type="file" name="imagen_archivo" id="imagen_archivo" accept="image/*" class="hidden" onchange="previewMainImage(this, 'preview_p1')">
                            
                            <div class="flex flex-col items-center">
                                <img id="preview_p1" src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="max-h-40 rounded border border-zinc-200 shadow-sm object-cover mb-2">
                                <span class="text-[11px] text-amber-850 font-semibold hover:underline">Clic para cambiar Foto 1 (Principal)</span>
                            </div>
                        </div>

                        <!-- Foto 2 (Secundaria) -->
                        <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-xl p-4 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_secundaria_archivo').click()">
                            <span class="block text-xs font-extrabold uppercase tracking-wider text-amber-900 mb-2">Foto 2 (Secundaria - Hover)</span>
                            <input type="file" name="imagen_secundaria_archivo" id="imagen_secundaria_archivo" accept="image/*" class="hidden" onchange="previewMainImage(this, 'preview_p2')">
                            
                            <div class="flex flex-col items-center">
                                @if($producto->imagen_secundaria_url)
                                    <img id="preview_p2" src="{{ $producto->imagen_secundaria_url }}" alt="{{ $producto->nombre }}" class="max-h-40 rounded border border-zinc-200 shadow-sm object-cover mb-2">
                                @else
                                    <img id="preview_p2" src="#" alt="Sin Foto 2" class="hidden max-h-40 rounded border border-zinc-200 shadow-sm object-cover mb-2">
                                    <div id="holder_p2" class="space-y-2 py-4">
                                        <svg class="mx-auto h-8 w-8 text-amber-800/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="text-xs font-bold text-zinc-700">Subir Foto 2 (Secundaria)</div>
                                    </div>
                                @endif
                                <span class="text-[11px] text-amber-850 font-semibold hover:underline">Clic para seleccionar / cambiar Foto 2</span>
                            </div>
                        </div>
                    </div>

                    @error('imagen_archivo')
                        <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
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

                <!-- Gestión de Acabados y Materiales del Mueble -->
                <div class="sm:col-span-2 border-t border-zinc-200 pt-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-800">
                                🪵 Acabados y Materiales del Mueble
                            </label>
                            <p class="text-xs text-zinc-500 mt-0.5">
                                Sube la imagen de la muestra del material, escribe el nombre del material y a un lado sube la foto del mueble con ese material.
                            </p>
                        </div>
                        <button type="button" onclick="agregarAcabadoRow()" class="bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold px-3 py-1.5 rounded transition-colors flex items-center space-x-1 cursor-pointer">
                            <span>+ Agregar acabado</span>
                        </button>
                    </div>

                    <div id="acabados-container" class="space-y-3">
                        @php
                            $acabadosExistentes = $producto->acabados_lista;
                        @endphp
                        @foreach($acabadosExistentes as $idx => $acab)
                            <div class="acabado-row flex flex-col md:flex-row items-center gap-3 p-3.5 bg-zinc-50 border border-zinc-200 rounded-xl">
                                <input type="hidden" name="acabados_materiales_existentes[]" value="{{ $acab['material_imagen'] ?? '' }}">
                                <input type="hidden" name="acabados_muebles_existentes[]" value="{{ $acab['mueble_imagen'] ?? '' }}">

                                <!-- 1. Muestra del Material -->
                                <div class="w-full md:w-1/3">
                                    <label class="block text-[10px] font-bold uppercase text-zinc-500 mb-1">1. Muestra del Material</label>
                                    <div class="flex items-center space-x-2">
                                        @if(!empty($acab['material_imagen']))
                                            <img src="{{ $acab['material_imagen'] }}" class="h-8 w-8 object-cover rounded border border-zinc-300 flex-shrink-0">
                                        @endif
                                        <input type="file" name="acabados_materiales[{{ $idx }}]" accept="image/*" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                                    </div>
                                </div>

                                <!-- 2. Nombre del Material -->
                                <div class="w-full md:w-1/3">
                                    <label class="block text-[10px] font-bold uppercase text-zinc-500 mb-1">2. Nombre del Material</label>
                                    <input type="text" name="acabados_nombres[]" required value="{{ $acab['nombre'] }}" placeholder="Ej: Nogal Oscuro" class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                </div>

                                <!-- 3. Foto del Mueble con este Material -->
                                <div class="w-full md:w-1/3 flex items-end gap-2">
                                    <div class="flex-grow">
                                        <label class="block text-[10px] font-bold uppercase text-zinc-500 mb-1">3. Foto Mueble con Material</label>
                                        <div class="flex items-center space-x-2">
                                            @if(!empty($acab['mueble_imagen']))
                                                <img src="{{ $acab['mueble_imagen'] }}" class="h-8 w-8 object-cover rounded border border-zinc-300 flex-shrink-0">
                                            @endif
                                            <input type="file" name="acabados_muebles[{{ $idx }}]" accept="image/*" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                                        </div>
                                    </div>
                                    <button type="button" onclick="eliminarAcabadoRow(this)" class="text-rose-600 hover:text-rose-800 p-2 cursor-pointer mb-0.5" title="Eliminar acabado">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
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

    <script>
        function previewMainImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const holder = document.getElementById('holder_' + previewId.split('_')[1]);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (holder) holder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        let acabadoIndexCount = {{ count($acabadosExistentes) }};
        function agregarAcabadoRow() {
            const container = document.getElementById('acabados-container');
            const row = document.createElement('div');
            row.className = 'acabado-row flex flex-col md:flex-row items-center gap-3 p-3.5 bg-zinc-50 border border-zinc-200 rounded-xl';
            row.innerHTML = `
                <input type="hidden" name="acabados_materiales_existentes[]" value="">
                <input type="hidden" name="acabados_muebles_existentes[]" value="">
                <div class="w-full md:w-1/3">
                    <label class="block text-[10px] font-bold uppercase text-zinc-500 mb-1">1. Muestra del Material</label>
                    <input type="file" name="acabados_materiales[${acabadoIndexCount}]" accept="image/*" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                </div>
                <div class="w-full md:w-1/3">
                    <label class="block text-[10px] font-bold uppercase text-zinc-500 mb-1">2. Nombre del Material</label>
                    <input type="text" name="acabados_nombres[]" required placeholder="Ej: Nogal Oscuro, Mármol..." class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                </div>
                <div class="w-full md:w-1/3 flex items-end gap-2">
                    <div class="flex-grow">
                        <label class="block text-[10px] font-bold uppercase text-zinc-500 mb-1">3. Foto Mueble con Material</label>
                        <input type="file" name="acabados_muebles[${acabadoIndexCount}]" accept="image/*" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                    </div>
                    <button type="button" onclick="eliminarAcabadoRow(this)" class="text-rose-600 hover:text-rose-800 p-2 cursor-pointer mb-0.5" title="Eliminar acabado">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(row);
            acabadoIndexCount++;
        }

        function eliminarAcabadoRow(btn) {
            const rows = document.querySelectorAll('.acabado-row');
            if (rows.length > 1) {
                btn.closest('.acabado-row').remove();
            } else {
                alert('Debes mantener al menos 1 acabado para el mueble.');
            }
        }
    </script>
@endsection
