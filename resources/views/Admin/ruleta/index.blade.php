@extends('layouts.admin')

@section('contenido')
    <div class="px-6 sm:px-8 py-8 max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200 pb-5">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Configuración de Marketing</span>
                <h1 class="serif-title text-3xl font-bold text-stone-950 mt-1">Ruleta de Premios para Nuevos Usuarios</h1>
                <p class="text-stone-600 text-sm mt-1">Personaliza las 3 opciones de la ruleta de bienvenida, sus descuentos y la paleta de colores cálidos de la marca.</p>
            </div>
        </div>

        <!-- Formulario de Configuración -->
        <form action="{{ route('admin.ruleta.actualizar') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach($opciones as $opcion)
                    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                        <div>
                            <!-- Header de la tarjeta con contraste garantizado -->
                            <div class="px-6 py-4 flex items-center justify-between bg-stone-950 text-white border-b border-stone-800">
                                <div class="flex items-center space-x-3">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-full border border-amber-500/40 bg-amber-500/20 text-amber-300">
                                        Opción #{{ $opcion->posicion }}
                                    </span>
                                    <div class="flex items-center space-x-1.5 px-2.5 py-1 rounded-full border border-stone-700 bg-stone-900 text-[11px] font-medium text-stone-200">
                                        <span class="w-3 h-3 rounded-full border border-white/40 shadow-sm" style="background-color: {{ $opcion->color_bg }};"></span>
                                        <span>Color Rueda</span>
                                    </div>
                                </div>
                                <label class="inline-flex items-center space-x-2 text-xs font-semibold cursor-pointer text-amber-200 hover:text-amber-100">
                                    <input type="checkbox" name="opciones[{{ $opcion->posicion }}][activo]" value="1" {{ $opcion->activo ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500 bg-stone-900 border-stone-700">
                                    <span>Activa</span>
                                </label>
                            </div>

                            <div class="p-6 space-y-5">
                                <!-- Título del Premio -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                                        Título del Premio <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" 
                                           name="opciones[{{ $opcion->posicion }}][titulo]" 
                                           value="{{ old('opciones.'.$opcion->posicion.'.titulo', $opcion->titulo) }}" 
                                           required 
                                           placeholder="Ej. 15% OFF en tu primera compra" 
                                           class="w-full bg-stone-50 border border-stone-200 rounded-xl text-sm px-3.5 py-2.5 text-stone-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">
                                </div>

                                <!-- Código de Cupón -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                                        Código del Cupón Asignado
                                    </label>
                                    <input type="text" 
                                           name="opciones[{{ $opcion->posicion }}][codigo_cupon]" 
                                           value="{{ old('opciones.'.$opcion->posicion.'.codigo_cupon', $opcion->codigo_cupon) }}" 
                                           placeholder="Ej. RULETA15" 
                                           class="w-full bg-stone-50 border border-stone-200 rounded-xl text-sm px-3.5 py-2.5 font-mono uppercase text-stone-900 font-bold focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">
                                </div>

                                <!-- Tipo de Descuento -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                                        Tipo de Beneficio <span class="text-rose-600">*</span>
                                    </label>
                                    <select name="opciones[{{ $opcion->posicion }}][tipo_descuento]" 
                                            class="w-full bg-stone-50 border border-stone-200 rounded-xl text-sm px-3.5 py-2.5 text-stone-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">
                                        <option value="porcentaje" {{ $opcion->tipo_descuento == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                                        <option value="fijo" {{ $opcion->tipo_descuento == 'fijo' ? 'selected' : '' }}>Monto Fijo ($ MXN)</option>
                                        <option value="envio_gratis" {{ $opcion->tipo_descuento == 'envio_gratis' ? 'selected' : '' }}>Envío Gratis ($0.00)</option>
                                    </select>
                                </div>

                                <!-- Valor de Descuento -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                                        Valor del Descuento / Premio <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           name="opciones[{{ $opcion->posicion }}][descuento_valor]" 
                                           value="{{ old('opciones.'.$opcion->posicion.'.descuento_valor', $opcion->descuento_valor) }}" 
                                           required 
                                           class="w-full bg-stone-50 border border-stone-200 rounded-xl text-sm px-3.5 py-2.5 text-stone-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">
                                </div>

                                <!-- Tiempo Límite en Minutos -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                                        Tiempo Límite para Reclamar (Minutos) <span class="text-rose-600">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" 
                                               min="1" 
                                               max="1440" 
                                               name="opciones[{{ $opcion->posicion }}][tiempo_minutos]" 
                                               value="{{ old('opciones.'.$opcion->posicion.'.tiempo_minutos', $opcion->tiempo_minutos) }}" 
                                               required 
                                               class="w-full bg-stone-50 border border-stone-200 rounded-xl text-sm px-3.5 py-2.5 pr-20 text-stone-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">
                                        <span class="absolute right-3 top-2.5 text-xs font-bold text-stone-400">minutos</span>
                                    </div>
                                </div>

                                <!-- Color de la sección (Paleta Clara y Cálida) -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                                        Color del Sector (Paleta Marca)
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" 
                                               id="color_picker_{{ $opcion->posicion }}"
                                               name="opciones[{{ $opcion->posicion }}][color_bg]" 
                                               value="{{ $opcion->color_bg }}" 
                                               onchange="document.getElementById('color_text_{{ $opcion->posicion }}').value = this.value"
                                               class="h-10 w-14 rounded-lg cursor-pointer border border-stone-300 p-0.5">
                                        <input type="text" 
                                               id="color_text_{{ $opcion->posicion }}"
                                               value="{{ $opcion->color_bg }}" 
                                               readonly 
                                               class="bg-stone-100 border border-stone-200 rounded-lg text-xs px-3 py-2 text-stone-700 font-mono w-28 font-bold text-center">
                                    </div>
                                    
                                    <!-- Paleta de Colores Claros de Alta Legibilidad -->
                                    <div class="mt-3 space-y-1.5">
                                        <div class="flex items-center space-x-2 flex-wrap gap-y-1.5">
                                            <span class="text-[10px] text-amber-800 font-bold uppercase w-full sm:w-auto">✨ Colores Claros (Recomendados):</span>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#FFF5EA')" class="w-6 h-6 rounded-full bg-[#FFF5EA] border border-stone-300 shadow hover:scale-110 transition-transform" title="Crema Cálido"></button>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#FDE8CD')" class="w-6 h-6 rounded-full bg-[#FDE8CD] border border-stone-300 shadow hover:scale-110 transition-transform" title="Arena Marfil"></button>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#F4F1EA')" class="w-6 h-6 rounded-full bg-[#F4F1EA] border border-stone-300 shadow hover:scale-110 transition-transform" title="Beige Lino"></button>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#FDEAE6')" class="w-6 h-6 rounded-full bg-[#FDEAE6] border border-stone-300 shadow hover:scale-110 transition-transform" title="Rosa Suave"></button>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#FFFFFF')" class="w-6 h-6 rounded-full bg-[#FFFFFF] border border-stone-300 shadow hover:scale-110 transition-transform" title="Blanco Nieve"></button>
                                        </div>
                                        <div class="flex items-center space-x-2 flex-wrap gap-y-1.5 pt-1">
                                            <span class="text-[10px] text-stone-400 font-bold uppercase w-full sm:w-auto">Acentuados:</span>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#C09A75')" class="w-6 h-6 rounded-full bg-[#C09A75] border border-stone-300 shadow hover:scale-110 transition-transform" title="Dorado Cálido"></button>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#88674B')" class="w-6 h-6 rounded-full bg-[#88674B] border border-stone-300 shadow hover:scale-110 transition-transform" title="Nogal Maderable"></button>
                                            <button type="button" onclick="setRuletaColor({{ $opcion->posicion }}, '#2B241A')" class="w-6 h-6 rounded-full bg-[#2B241A] border border-stone-300 shadow hover:scale-110 transition-transform" title="Espresso Oscuro"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer preview -->
                        <div class="px-6 py-3.5 bg-stone-900 border-t border-stone-800 text-xs text-stone-300 flex items-center justify-between">
                            <span class="font-medium">Vista previa:</span>
                            <div class="flex items-center space-x-2 font-semibold">
                                <span class="w-3 h-3 rounded-full border border-white/30" style="background-color: {{ $opcion->color_bg }};"></span>
                                <strong class="text-amber-200">{{ $opcion->titulo }}</strong>
                                <span class="text-stone-400">({{ $opcion->tiempo_minutos }} min)</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white font-bold text-sm px-8 py-3.5 rounded-xl shadow-lg hover:shadow-amber-900/30 transition-all duration-300 flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Guardar Cambios de la Ruleta</span>
                </button>
            </div>
        </form>
    </div>

    <script>
    function setRuletaColor(pos, hex) {
        const picker = document.getElementById('color_picker_' + pos);
        const input = document.getElementById('color_text_' + pos);
        if (picker) picker.value = hex;
        if (input) input.value = hex;
    }
    </script>
@endsection
