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

                <!-- Proveedor (Uso Interno Admin / SKU) -->
                <div>
                    <label for="proveedor" class="block text-xs font-semibold text-amber-900 uppercase tracking-wider mb-1">
                        🏢 Proveedor (Uso Interno SKU)
                    </label>
                    <select name="proveedor" id="proveedor" required class="w-full bg-amber-50/50 border border-amber-300 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700 font-medium">
                        <option value="Casa Tapier" {{ old('proveedor', 'Casa Tapier') === 'Casa Tapier' ? 'selected' : '' }}>Casa Tapier (CT)</option>
                        <option value="Muebles Samar" {{ old('proveedor') === 'Muebles Samar' ? 'selected' : '' }}>Muebles Samar (MS)</option>
                    </select>
                    <span class="text-[10px] text-zinc-400 block mt-1">* Solo visible en Admin. Usado para SKU (CT / MS)</span>
                    @error('proveedor')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tipo de Mueble (Código SKU 01 - 10) -->
                <div>
                    <label for="tipo_mueble" class="block text-xs font-semibold text-amber-900 uppercase tracking-wider mb-1">
                        🏷️ Tipo de Mueble (Código SKU)
                    </label>
                    <select name="tipo_mueble" id="tipo_mueble" required class="w-full bg-amber-50/50 border border-amber-300 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700 font-medium">
                        <option value="01" {{ old('tipo_mueble', '01') === '01' ? 'selected' : '' }}>01 - Silla</option>
                        <option value="02" {{ old('tipo_mueble') === '02' ? 'selected' : '' }}>02 - Sillón Comedor</option>
                        <option value="03" {{ old('tipo_mueble') === '03' ? 'selected' : '' }}>03 - Sillón</option>
                        <option value="04" {{ old('tipo_mueble') === '04' ? 'selected' : '' }}>04 - Sala Modular</option>
                        <option value="05" {{ old('tipo_mueble') === '05' ? 'selected' : '' }}>05 - Recámara</option>
                        <option value="06" {{ old('tipo_mueble') === '06' ? 'selected' : '' }}>06 - Cama</option>
                        <option value="07" {{ old('tipo_mueble') === '07' ? 'selected' : '' }}>07 - Taburete</option>
                        <option value="08" {{ old('tipo_mueble') === '08' ? 'selected' : '' }}>08 - Buró</option>
                        <option value="09" {{ old('tipo_mueble') === '09' ? 'selected' : '' }}>09 - Recibidor</option>
                        <option value="10" {{ old('tipo_mueble') === '10' ? 'selected' : '' }}>10 - Mesa Comedor</option>
                    </select>
                    @error('tipo_mueble')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Número de Piezas (Uso Interno SKU) -->
                <div>
                    <label for="numero_piezas" class="block text-xs font-semibold text-amber-900 uppercase tracking-wider mb-1">
                        🧩 Número de Piezas
                    </label>
                    <input type="number" min="1" max="99" name="numero_piezas" id="numero_piezas" required value="{{ old('numero_piezas', 1) }}" placeholder="Ej: 1" class="w-full bg-amber-50/50 border border-amber-300 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700 font-medium">
                    <span class="text-[10px] text-zinc-400 block mt-1">* Por defecto 01. Se formatea a 2 dígitos en el SKU</span>
                    @error('numero_piezas')
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

                <!-- Subir Imágenes (Foto 1 Principal & Foto 2 Secundaria) -->
                <div class="sm:col-span-2 space-y-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-800">
                        📸 Fotografías del Mueble (Foto 1 Principal y Foto 2 Secundaria)
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Foto 1 (Principal) -->
                        <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-xl p-4 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_archivo').click()">
                            <span class="block text-xs font-extrabold uppercase tracking-wider text-amber-900 mb-2">Foto 1 (Principal) *</span>
                            <input type="file" name="imagen_archivo" id="imagen_archivo" accept="image/*" required class="hidden" onchange="previewMainImage(this, 'preview_p1', 'holder_p1')">
                            
                            <div id="holder_p1" class="space-y-2 py-4">
                                <svg class="mx-auto h-8 w-8 text-amber-800/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="text-xs font-bold text-zinc-700">Subir Foto 1 (Principal)</div>
                                <p class="text-[10px] text-zinc-400">Imagen fija por defecto</p>
                            </div>

                            <img id="preview_p1" src="#" alt="Foto 1" class="hidden max-h-40 mx-auto rounded border border-zinc-200 shadow-sm object-cover">
                        </div>

                        <!-- Foto 2 (Secundaria) -->
                        <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-xl p-4 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_secundaria_archivo').click()">
                            <span class="block text-xs font-extrabold uppercase tracking-wider text-amber-900 mb-2">Foto 2 (Secundaria)</span>
                            <input type="file" name="imagen_secundaria_archivo" id="imagen_secundaria_archivo" accept="image/*" class="hidden" onchange="previewMainImage(this, 'preview_p2', 'holder_p2')">
                            
                            <div id="holder_p2" class="space-y-2 py-4">
                                <svg class="mx-auto h-8 w-8 text-amber-800/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="text-xs font-bold text-zinc-700">Subir Foto 2 (Secundaria)</div>
                                <p class="text-[10px] text-zinc-400">Aparece al pasar el cursor (hover)</p>
                            </div>

                            <img id="preview_p2" src="#" alt="Foto 2" class="hidden max-h-40 mx-auto rounded border border-zinc-200 shadow-sm object-cover">
                        </div>
                    </div>

                    @error('imagen_archivo')
                        <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Subir Imágenes de Dimensiones (Vista Lateral, Frontal, Superior) -->
                <div class="sm:col-span-2 space-y-3 border-t border-zinc-200 pt-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-800">
                        📐 Fotografías de Dimensiones del Mueble
                    </label>
                    <p class="text-xs text-zinc-500">Sube los esquemas o fotografías de las medidas del mueble (Lateral, Frontal y Superior).</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                        <!-- Vista Lateral -->
                        <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-xl p-4 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_dimension_lateral').click()">
                            <span class="block text-[11px] font-extrabold uppercase tracking-wider text-amber-900 mb-2">Vista Lateral</span>
                            <input type="file" name="imagen_dimension_lateral" id="imagen_dimension_lateral" accept="image/*" class="hidden" onchange="previewMainImage(this, 'preview_dim_lat', 'holder_dim_lat')">
                            
                            <div id="holder_dim_lat" class="space-y-2 py-3">
                                <svg class="mx-auto h-7 w-7 text-amber-800/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="text-[11px] font-bold text-zinc-700">Subir Vista Lateral</div>
                                <p class="text-[10px] text-zinc-400">Esquema o foto de perfil</p>
                            </div>

                            <img id="preview_dim_lat" src="#" alt="Vista Lateral" class="hidden max-h-36 mx-auto rounded border border-zinc-200 shadow-sm object-contain">
                        </div>

                        <!-- Vista Frontal -->
                        <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-xl p-4 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_dimension_frontal').click()">
                            <span class="block text-[11px] font-extrabold uppercase tracking-wider text-amber-900 mb-2">Vista Frontal</span>
                            <input type="file" name="imagen_dimension_frontal" id="imagen_dimension_frontal" accept="image/*" class="hidden" onchange="previewMainImage(this, 'preview_dim_fro', 'holder_dim_fro')">
                            
                            <div id="holder_dim_fro" class="space-y-2 py-3">
                                <svg class="mx-auto h-7 w-7 text-amber-800/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="text-[11px] font-bold text-zinc-700">Subir Vista Frontal</div>
                                <p class="text-[10px] text-zinc-400">Esquema o foto de frente</p>
                            </div>

                            <img id="preview_dim_fro" src="#" alt="Vista Frontal" class="hidden max-h-36 mx-auto rounded border border-zinc-200 shadow-sm object-contain">
                        </div>

                        <!-- Vista Superior -->
                        <div class="border-2 border-dashed border-zinc-200 hover:border-amber-700 rounded-xl p-4 bg-zinc-50 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('imagen_dimension_superior').click()">
                            <span class="block text-[11px] font-extrabold uppercase tracking-wider text-amber-900 mb-2">Vista Superior</span>
                            <input type="file" name="imagen_dimension_superior" id="imagen_dimension_superior" accept="image/*" class="hidden" onchange="previewMainImage(this, 'preview_dim_sup', 'holder_dim_sup')">
                            
                            <div id="holder_dim_sup" class="space-y-2 py-3">
                                <svg class="mx-auto h-7 w-7 text-amber-800/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="text-[11px] font-bold text-zinc-700">Subir Vista Superior</div>
                                <p class="text-[10px] text-zinc-400">Esquema o foto desde arriba</p>
                            </div>

                            <img id="preview_dim_sup" src="#" alt="Vista Superior" class="hidden max-h-36 mx-auto rounded border border-zinc-200 shadow-sm object-contain">
                        </div>
                    </div>
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

                    <div id="acabados-container" class="space-y-4">
                        <!-- Acabado 1 Default -->
                        <div class="acabado-row p-4 bg-zinc-50/90 border border-zinc-200 rounded-2xl space-y-3">
                            <input type="hidden" name="acabados_skus[]" value="SKU-AUTO-01">

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
                                <!-- 1. SKU (No editable) -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400 mb-1">
                                        1. SKU (Auto-generado)
                                    </label>
                                    <input type="text" value="SKU-AUTO-01" disabled readonly class="w-full bg-zinc-100 border border-zinc-200 rounded-lg text-xs px-2.5 py-2 font-mono text-zinc-500 cursor-not-allowed">
                                </div>

                                <!-- 2. Nombre del Acabado -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                                        2. Nombre del Acabado
                                    </label>
                                    <input type="text" name="acabados_nombres[]" required value="Madera Natural" placeholder="Ej: Roble Claro, Nogal, Mármol..." class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-2.5 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                </div>

                                <!-- 3. Precio ($) -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                                        3. Precio ($ MXN)
                                    </label>
                                    <input type="number" step="0.01" min="0" name="acabados_precios[]" required placeholder="Precio Mueble" class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-2.5 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                </div>

                                <!-- 4. Stock -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                                        4. Stock (Inventario)
                                    </label>
                                    <input type="number" min="0" name="acabados_stocks[]" required placeholder="Stock Mueble" class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-2.5 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end pt-2 border-t border-zinc-200/60">
                                <!-- 5. Muestra del Material -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                                        5. Muestra del Material (Opcional)
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <input type="file" name="acabados_materiales[0]" accept="image/*" onchange="previewAcabadoImage(this)" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                                    </div>
                                </div>

                                <!-- 6. Foto Mueble con Material -->
                                <div class="flex items-end gap-2">
                                    <div class="flex-grow">
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                                            6. Foto Mueble con este Material
                                        </label>
                                        <div class="flex items-center space-x-2">
                                            <input type="file" name="acabados_muebles[0]" accept="image/*" onchange="previewAcabadoImage(this)" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                                        </div>
                                    </div>
                                    <button type="button" onclick="eliminarAcabadoRow(this)" class="text-rose-600 hover:text-rose-800 p-2 cursor-pointer mb-0.5" title="Eliminar acabado">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
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
        function previewMainImage(input, previewId, holderId) {
            const preview = document.getElementById(previewId);
            const holder = document.getElementById(holderId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    holder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewAcabadoImage(input) {
            if (input.files && input.files[0]) {
                let parentDiv = input.parentNode;
                let prevImg = parentDiv.querySelector('img');
                if (!prevImg) {
                    prevImg = document.createElement('img');
                    prevImg.className = 'h-8 w-8 object-cover rounded border border-zinc-300 flex-shrink-0 mr-2';
                    parentDiv.insertBefore(prevImg, input);
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    prevImg.src = e.target.result;
                    prevImg.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        let acabadoIndexCount = 1;
        function agregarAcabadoRow() {
            const container = document.getElementById('acabados-container');
            const row = document.createElement('div');
            row.className = 'acabado-row p-4 bg-zinc-50/90 border border-zinc-200 rounded-2xl space-y-3';
            const generatedSku = 'SKU-AUTO-' + String(acabadoIndexCount + 1).padStart(2, '0');
            const firstPrice = document.querySelector('input[name="acabados_precios[]"]')?.value || '0.00';
            const firstStock = document.querySelector('input[name="acabados_stocks[]"]')?.value || '10';

            row.innerHTML = `
                <input type="hidden" name="acabados_skus[]" value="${generatedSku}">

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400 mb-1">
                            1. SKU (Auto-generado)
                        </label>
                        <input type="text" value="${generatedSku}" disabled readonly class="w-full bg-zinc-100 border border-zinc-200 rounded-lg text-xs px-2.5 py-2 font-mono text-zinc-500 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                            2. Nombre del Acabado
                        </label>
                        <input type="text" name="acabados_nombres[]" required placeholder="Ej: Nogal Oscuro" class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-2.5 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                            3. Precio ($ MXN)
                        </label>
                        <input type="number" step="0.01" min="0" name="acabados_precios[]" required value="${firstPrice}" class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-2.5 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                            4. Stock (Inventario)
                        </label>
                        <input type="number" min="0" name="acabados_stocks[]" required value="${firstStock}" class="w-full bg-white border border-zinc-200 rounded-lg text-xs px-2.5 py-2 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end pt-2 border-t border-zinc-200/60">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                            5. Muestra del Material (Opcional)
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="file" name="acabados_materiales[${acabadoIndexCount}]" accept="image/*" onchange="previewAcabadoImage(this)" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <div class="flex-grow">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                                6. Foto Mueble con Material
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="file" name="acabados_muebles[${acabadoIndexCount}]" accept="image/*" onchange="previewAcabadoImage(this)" class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                            </div>
                        </div>
                        <button type="button" onclick="eliminarAcabadoRow(this)" class="text-rose-600 hover:text-rose-800 p-2 cursor-pointer mb-0.5" title="Eliminar acabado">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
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
