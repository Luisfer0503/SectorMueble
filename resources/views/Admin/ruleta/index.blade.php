@extends('layouts.admin')

@section('contenido')
    <div class="px-6 sm:px-8 py-8 max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-zinc-200 pb-5">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Configuración de Marketing</span>
                <h1 class="serif-title text-3xl font-bold text-zinc-950 mt-1">Ruleta de Premios para Nuevos Usuarios</h1>
                <p class="text-zinc-500 text-sm mt-1">Personaliza las 3 opciones de la ruleta de bienvenida, sus descuentos y el tiempo límite para canjearlas.</p>
            </div>
        </div>

        <!-- Formulario de Configuración -->
        <form action="{{ route('admin.ruleta.actualizar') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach($opciones as $opcion)
                    <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
                        <div>
                            <!-- Header de la tarjeta con color distintivo -->
                            <div class="px-6 py-4 flex items-center justify-between text-white" style="background-color: {{ $opcion->color_bg }};">
                                <div class="flex items-center space-x-2">
                                    <span class="bg-white/20 text-white font-extrabold text-xs px-2.5 py-1 rounded-full uppercase tracking-wider">
                                        Opción #{{ $opcion->posicion }}
                                    </span>
                                </div>
                                <label class="inline-flex items-center space-x-2 text-xs font-semibold cursor-pointer">
                                    <input type="checkbox" name="opciones[{{ $opcion->posicion }}][activo]" value="1" {{ $opcion->activo ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                                    <span>Activa</span>
                                </label>
                            </div>

                            <div class="p-6 space-y-5">
                                <!-- Título del Premio -->
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">
                                        Título del Premio <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="opciones[{{ $opcion->posicion }}][titulo]" 
                                           value="{{ old('opciones.'.$opcion->posicion.'.titulo', $opcion->titulo) }}" 
                                           required 
                                           placeholder="Ej. 15% OFF en tu primera compra" 
                                           class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white">
                                </div>

                                <!-- Código de Cupón -->
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">
                                        Código del Cupón Asignado
                                    </label>
                                    <input type="text" 
                                           name="opciones[{{ $opcion->posicion }}][codigo_cupon]" 
                                           value="{{ old('opciones.'.$opcion->posicion.'.codigo_cupon', $opcion->codigo_cupon) }}" 
                                           placeholder="Ej. RULETA15" 
                                           class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3.5 py-2.5 font-mono uppercase focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white">
                                </div>

                                <!-- Tipo de Descuento -->
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">
                                        Tipo de Beneficio <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="opciones[{{ $opcion->posicion }}][tipo_descuento]" 
                                            class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white">
                                        <option value="porcentaje" {{ $opcion->tipo_descuento == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                                        <option value="fijo" {{ $opcion->tipo_descuento == 'fijo' ? 'selected' : '' }}>Monto Fijo ($ MXN)</option>
                                        <option value="envio_gratis" {{ $opcion->tipo_descuento == 'envio_gratis' ? 'selected' : '' }}>Envío Gratis ($0.00)</option>
                                    </select>
                                </div>

                                <!-- Valor de Descuento -->
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">
                                        Valor del Descuento / Premio <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           name="opciones[{{ $opcion->posicion }}][descuento_valor]" 
                                           value="{{ old('opciones.'.$opcion->posicion.'.descuento_valor', $opcion->descuento_valor) }}" 
                                           required 
                                           class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white">
                                </div>

                                <!-- Tiempo Límite en Minutos -->
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">
                                        Tiempo Límite para Reclamar (Minutos) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" 
                                               min="1" 
                                               max="1440" 
                                               name="opciones[{{ $opcion->posicion }}][tiempo_minutos]" 
                                               value="{{ old('opciones.'.$opcion->posicion.'.tiempo_minutos', $opcion->tiempo_minutos) }}" 
                                               required 
                                               class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3.5 py-2.5 pr-20 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white">
                                        <span class="absolute right-3 top-2.5 text-xs font-semibold text-zinc-400">minutos</span>
                                    </div>
                                </div>

                                <!-- Color de la sección -->
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">
                                        Color del Sector en la Ruleta
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" 
                                               name="opciones[{{ $opcion->posicion }}][color_bg]" 
                                               value="{{ $opcion->color_bg }}" 
                                               class="h-9 w-12 rounded cursor-pointer border border-zinc-300">
                                        <input type="text" 
                                               value="{{ $opcion->color_bg }}" 
                                               readonly 
                                               class="bg-zinc-100 border border-zinc-200 rounded text-xs px-3 py-2 text-zinc-600 font-mono w-28">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer preview -->
                        <div class="px-6 py-3 bg-zinc-50 border-t border-zinc-100 text-xs text-zinc-500">
                            Vista previa: <strong class="text-zinc-800">{{ $opcion->titulo }}</strong> ({{ $opcion->tiempo_minutos }} min)
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white font-semibold text-sm px-8 py-3.5 rounded shadow hover:shadow-md transition-all duration-300 flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Guardar Cambios de la Ruleta</span>
                </button>
            </div>
        </form>
    </div>
@endsection
