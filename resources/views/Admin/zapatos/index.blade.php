@extends('layouts.admin')

@section('contenido')
    <!-- Meta CSRF para peticiones AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Engine Tesseract OCR para lectura local inmediata de etiquetas de fotos -->
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <!-- Contenedor Principal con Estilo Minimalista y Ergonómico -->
    <div class="space-y-8">
        
        <!-- Header de Control de Inventario -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-slate-900 text-amber-400 rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Módulo de Gestión de Calzado</span>
                    <h1 class="serif-title text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Inventario de Zapatos (Escáner IA)</h1>
                    <p class="text-slate-500 text-xs md:text-sm mt-1">Escanea fotos de etiquetas de calzado o ingresa datos manualmente. Generación automática de Clave Alterna.</p>
                </div>
            </div>

            <!-- Botones Principales Destacados (Súper fácil de ver y pulsar) -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Botón Principal Héroe: Tomar Foto -->
                <button onclick="abrirModalEscaner()" class="flex-1 sm:flex-initial inline-flex items-center justify-center px-6 py-3.5 bg-slate-900 hover:bg-black text-white font-bold text-xs md:text-sm rounded-xl shadow-md hover:shadow-lg transition-all space-x-2.5 group cursor-pointer">
                    <svg class="w-5 h-5 text-amber-400 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9"/>
                    </svg>
                    <span>📷 Tomar Foto / Escanear Zapato</span>
                </button>

                <!-- Botón Descargar Excel -->
                <a href="{{ route('admin.zapatos.excel') }}" class="inline-flex items-center justify-center px-5 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs md:text-sm rounded-xl shadow-sm hover:shadow transition-all space-x-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>📥 Descargar Excel</span>
                </a>

                <!-- Botón Captura Manual -->
                <button onclick="abrirCapturaManual()" class="inline-flex items-center justify-center px-4 py-3.5 bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs md:text-sm rounded-xl border border-slate-300 shadow-sm transition-all space-x-2">
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Captura Manual</span>
                </button>

                <!-- Botón Clave API IA -->
                <button onclick="abrirModalApiKey()" class="inline-flex items-center justify-center px-4 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs md:text-sm rounded-xl border border-slate-300 transition-all space-x-2" title="Configurar Clave API de Gemini Vision">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span>Clave API IA</span>
                </button>
            </div>
        </div>

        <!-- Tarjetas de Métricas de Inventario Minimalistas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Modelos Registrados</span>
                    <span class="text-3xl font-extrabold text-slate-900 block mt-1 font-sans">{{ $totalModelos }}</span>
                </div>
                <div class="w-12 h-12 bg-slate-100 text-slate-800 rounded-xl flex items-center justify-center border border-slate-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pares en Stock (EXIST.)</span>
                    <span class="text-3xl font-extrabold text-emerald-700 block mt-1 font-sans">{{ number_format($totalPares) }}</span>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center border border-emerald-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Valor Total Inventario</span>
                    <span class="text-3xl font-extrabold text-slate-900 block mt-1 font-sans">$ {{ number_format($valorTotalInventario, 2, '.', ',') }}</span>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-800 rounded-xl flex items-center justify-center border border-amber-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sección de Tabla de Inventario -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <!-- Barra de Búsqueda de Inventario -->
            <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="serif-title text-xl font-bold text-slate-900">Catálogo de Zapatos Registrados</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Listado ordenado por Clave Alterna, Descripción, Precio y Stock.</p>
                </div>
                
                <form action="{{ route('admin.zapatos') }}" method="GET" class="w-full sm:w-80 flex items-center space-x-2">
                    <div class="relative w-full">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar clave, estilo, talla, color..." class="w-full pl-9 pr-4 py-2.5 text-xs font-semibold border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.zapatos') }}" class="text-xs text-slate-500 hover:text-slate-800 font-bold underline">Limpiar</a>
                    @endif
                </form>
            </div>

            @if($zapatos->isEmpty())
                <div class="text-center py-16 px-4">
                    <div class="w-20 h-20 bg-slate-100 text-slate-700 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                    </div>
                    <h3 class="serif-title text-lg font-bold text-slate-900">No hay zapatos registrados en inventario</h3>
                    <p class="text-slate-500 text-xs mt-1 max-w-md mx-auto">Toma una foto de tu calzado para que la Inteligencia Artificial analice la etiqueta e ingrese la clave automáticamente.</p>
                    <button onclick="abrirModalEscaner()" class="mt-5 inline-flex items-center px-5 py-2.5 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-black transition-all space-x-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Escanear Primer Zapato</span>
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-100 text-[11px] font-bold uppercase tracking-wider border-b border-slate-800">
                                <th class="py-4 px-4 text-center w-12">NO.</th>
                                <th class="py-4 px-5">CLAVE ALTERNA</th>
                                <th class="py-4 px-5">DESCRIPCION</th>
                                <th class="py-4 px-5 text-right">PRECIO 1</th>
                                <th class="py-4 px-5 text-center">EXIST.</th>
                                <th class="py-4 px-5 text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs bg-white">
                            @foreach($zapatos as $zapato)
                                <tr class="hover:bg-amber-50/40 transition-colors">
                                    <td class="py-4 px-4 text-center font-mono font-extrabold text-slate-400 text-xs">
                                        {{ $loop->iteration + ($zapatos->currentPage() - 1) * $zapatos->perPage() }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ $zapato->imagen_url }}" alt="{{ $zapato->estilo }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 shadow-sm bg-slate-100 flex-shrink-0">
                                            <span class="font-mono font-bold text-amber-950 bg-amber-100/90 px-3 py-1.5 rounded-lg border border-amber-300 inline-block shadow-sm text-xs">
                                                {{ $zapato->clave_alterna }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="font-extrabold text-slate-900 uppercase block text-xs tracking-tight">{{ $zapato->descripcion_completa }}</span>
                                        <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-500 mt-1">
                                            <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Estilo: <strong>{{ $zapato->estilo }}</strong></span>
                                            <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Material: <strong>{{ $zapato->material }}</strong></span>
                                            <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Color: <strong>{{ $zapato->color }}</strong></span>
                                            @if($zapato->bordado)
                                                <span class="bg-amber-100 text-amber-900 px-2 py-0.5 rounded border border-amber-300 font-bold">Bordado: {{ $zapato->bordado }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 text-right font-extrabold text-slate-900 font-sans text-sm">
                                        $ {{ number_format($zapato->precio, 2, '.', ',') }}
                                    </td>
                                    <td class="py-4 px-5 text-center">
                                        @if($zapato->cantidad > 5)
                                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">{{ $zapato->cantidad }}</span>
                                        @elseif($zapato->cantidad > 0)
                                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 border border-amber-300">{{ $zapato->cantidad }}</span>
                                        @else
                                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-rose-100 text-rose-900 border border-rose-300">0</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button onclick="abrirModalEditar({{ json_encode($zapato) }})" class="p-2 text-slate-700 hover:text-amber-900 bg-slate-100 hover:bg-amber-100 rounded-xl border border-slate-200 transition-colors" title="Editar zapato">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <a href="{{ route('admin.zapatos.eliminar', $zapato->id) }}" onclick="return confirm('¿Estás seguro de eliminar este zapato del inventario?')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl border border-slate-200 transition-colors" title="Eliminar zapato">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="p-5 border-t border-slate-200 bg-slate-50/50">
                    {{ $zapatos->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 1: ESCÁNER / CAPTURA DE FOTO DE ZAPATO -->
    <!-- ========================================== -->
    <div id="modalEscaner" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-slate-200 transition-all">
            <button onclick="cerrarModalEscaner()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-slate-900 text-amber-400 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-md">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    </svg>
                </div>
                <h3 class="serif-title text-2xl font-bold text-slate-900">Tomar Foto del Zapato</h3>
                <p class="text-xs text-slate-500 mt-1">Usa la cámara en vivo de tu dispositivo o selecciona una fotografía de la galería.</p>
            </div>

            <!-- Opción 1: Visor de Cámara WebCam -->
            <div id="contenedorCamara" class="hidden mb-4">
                <div class="relative bg-black rounded-2xl overflow-hidden aspect-video flex items-center justify-center border border-slate-800 shadow-inner">
                    <video id="webcamVideo" autoplay playsinline class="w-full h-full object-cover"></video>
                    <canvas id="webcamCanvas" class="hidden"></canvas>
                </div>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <button type="button" onclick="capturarDeCamara()" class="px-6 py-3 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow flex items-center space-x-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                        <span>Capturar Foto Ahora</span>
                    </button>
                    <button type="button" onclick="detenerCamara()" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl">
                        Cancelar Cámara
                    </button>
                </div>
            </div>

            <!-- Opción 2: Botones de Selección -->
            <div id="opcionesCaptura" class="space-y-3">
                <button type="button" onclick="iniciarCamara()" class="w-full py-4 px-4 bg-slate-900 hover:bg-black text-white font-bold text-xs rounded-xl shadow flex items-center justify-center space-x-2.5 transition-all">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Abrir Cámara en Vivo (WebCam / Celular)</span>
                </button>

                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-[10px] text-slate-400 font-bold uppercase tracking-widest">o bien</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <label class="w-full py-4 px-4 bg-amber-50/70 hover:bg-amber-100/80 border-2 border-dashed border-amber-300 text-amber-950 font-bold text-xs rounded-xl flex items-center justify-center space-x-2 cursor-pointer transition-colors">
                    <svg class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Seleccionar Imagen de Galería</span>
                    <input type="file" id="inputFotoArchivo" accept="image/*" capture="environment" onchange="subirArchivoSeleccionado(event)" class="hidden">
                </label>

                <button type="button" onclick="abrirCapturaManual()" class="w-full py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl border border-slate-300 flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Capturar Datos Manualmente</span>
                </button>
            </div>

            <!-- Spinner / Overlay de Procesamiento con IA -->
            <div id="loaderIA" class="hidden mt-6 text-center py-6 bg-slate-50 border border-slate-200 rounded-2xl">
                <div class="inline-block animate-spin w-10 h-10 border-4 border-slate-900 border-t-transparent rounded-full mb-3"></div>
                <h4 class="font-bold text-slate-900 text-sm">Analizando Zapato con Inteligencia Artificial...</h4>
                <p class="text-xs text-slate-500 mt-1">Reconociendo Estilo, Talla, Color, Material y Bordado...</p>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 2: CONFIRMACIÓN DE DATOS, CANTIDAD Y PRECIO -->
    <!-- ========================================== -->
    <div id="modalConfirmacion" class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative border border-slate-200 my-8">
            <button onclick="cerrarModalConfirmacion()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex items-center space-x-3.5 mb-6">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="serif-title text-xl font-bold text-slate-900">Confirmar e Ingresar a Inventario</h3>
                    <p class="text-xs text-slate-500">Verifica los datos reconocidos e ingresa la cantidad y el precio del zapato.</p>
                </div>
            </div>

            <form id="formGuardarZapato" onsubmit="guardarZapatoInventario(event)">
                <input type="hidden" id="confImagenPath" name="imagen_path">

                <!-- Preview de Foto + Atributos IA -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <div class="sm:col-span-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Foto Capturada</span>
                        <img id="confImagenPreview" src="" alt="Vista Previa" class="w-full h-32 object-cover rounded-xl border border-slate-200 shadow-sm bg-white">
                    </div>

                    <div class="sm:col-span-2 space-y-3">
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-700 uppercase">Estilo (ej. 1214, M-631)</label>
                            <input type="text" id="confEstilo" name="estilo" required placeholder="ej. 1214, M-631..." class="w-full mt-1 px-3.5 py-2 text-xs font-bold bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-700 uppercase">Número / Talla (ej. 22.0, 25.5)</label>
                            <input type="text" id="confNumero" name="numero" list="listaTallasDecimales" required placeholder="ej. 22.0, 25.5, 27.0" class="w-full mt-1 px-3.5 py-2 text-xs font-bold bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                            <datalist id="listaTallasDecimales">
                                <option value="20.0"></option>
                                <option value="20.5"></option>
                                <option value="21.0"></option>
                                <option value="21.5"></option>
                                <option value="22.0"></option>
                                <option value="22.5"></option>
                                <option value="23.0"></option>
                                <option value="23.5"></option>
                                <option value="24.0"></option>
                                <option value="24.5"></option>
                                <option value="25.0"></option>
                                <option value="25.5"></option>
                                <option value="26.0"></option>
                                <option value="26.5"></option>
                                <option value="27.0"></option>
                                <option value="27.5"></option>
                                <option value="28.0"></option>
                                <option value="28.5"></option>
                                <option value="29.0"></option>
                                <option value="29.5"></option>
                                <option value="30.0"></option>
                            </datalist>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-2">
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-700 uppercase">Color</label>
                        <input type="text" id="confColor" name="color" required placeholder="ej. Negro, Blanco" class="w-full mt-1 px-3 py-2 text-xs font-semibold bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-700 uppercase">Material</label>
                        <input type="text" id="confMaterial" name="material" required placeholder="ej. Sintético, Piel" class="w-full mt-1 px-3 py-2 text-xs font-semibold bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-700 uppercase">Bordado (Opcional)</label>
                        <input type="text" id="confBordado" name="bordado" placeholder="ej. Flor, Estrella..." class="w-full mt-1 px-3 py-2 text-xs font-semibold bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 mb-6 italic">* La Clave Alterna se genera automáticamente: M(Estilo)(Material)(Color)[Bordado]T(Talla).</p>

                <!-- Campos de Cantidad y Precio -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                            Cantidad (Stock / EXIST.) <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative mt-1">
                            <input type="number" id="confCantidad" name="cantidad" min="1" value="1" required class="w-full px-3 py-2 text-sm font-extrabold bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none text-slate-900">
                            <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-bold">pares</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                            Precio 1 ($) <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">$</span>
                            <input type="number" id="confPrecio" name="precio" step="0.01" min="0" placeholder="250.00" required class="w-full pl-7 pr-3 py-2 text-sm font-extrabold bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none text-slate-900">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarModalConfirmacion()" class="px-5 py-3 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" id="btnGuardarFinal" class="px-6 py-3 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Guardar en Inventario</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 3: EDITAR REGISTRO DE ZAPATO -->
    <!-- ========================================== -->
    <div id="modalEditar" class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-slate-200">
            <button onclick="cerrarModalEditar()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="serif-title text-2xl font-bold text-slate-900 mb-4">Editar Calzado de Inventario</h3>

            <form id="formActualizarZapato" method="POST" action="">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase">Estilo</label>
                        <input type="text" id="editEstilo" name="estilo" required class="w-full mt-1 px-3.5 py-2 text-xs font-bold border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase">Número / Talla (ej. 22.0)</label>
                            <input type="text" id="editNumero" name="numero" list="listaTallasDecimales" required class="w-full mt-1 px-3.5 py-2 text-xs font-bold border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase">Color</label>
                            <input type="text" id="editColor" name="color" required class="w-full mt-1 px-3.5 py-2 text-xs font-bold border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase">Material</label>
                            <input type="text" id="editMaterial" name="material" required class="w-full mt-1 px-3.5 py-2 text-xs font-bold border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase">Bordado (Opcional)</label>
                            <input type="text" id="editBordado" name="bordado" placeholder="ej. Flor..." class="w-full mt-1 px-3.5 py-2 text-xs font-bold border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase">Cantidad (Stock / EXIST.)</label>
                            <input type="number" id="editCantidad" name="cantidad" min="0" required class="w-full mt-1 px-3 py-2 text-xs font-extrabold border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase">Precio 1 ($)</label>
                            <input type="number" id="editPrecio" name="precio" step="0.01" min="0" required class="w-full mt-1 px-3 py-2 text-xs font-extrabold border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalEditar()" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl">Cancelar</button>
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 4: CONFIGURACIÓN DE CLAVE API GEMINI -->
    <!-- ========================================== -->
    <div id="modalApiKey" class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-md flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-6 sm:p-8 border border-slate-200">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <span class="p-2.5 bg-amber-100 text-amber-900 rounded-2xl">
                        <svg class="w-6 h-6 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="serif-title text-lg font-bold text-slate-900">Clave API de Inteligencia Artificial</h3>
                        <p class="text-xs text-slate-500">Google Gemini Vision AI (100% Gratuita)</p>
                    </div>
                </div>
                <button onclick="cerrarModalApiKey()" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form onsubmit="guardarApiKeyGemini(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Clave API (GEMINI_API_KEY)</label>
                        <input type="text" id="inputApiKey" value="{{ env('GEMINI_API_KEY') }}" required placeholder="Pega aquí tu clave API de Google AI Studio" class="w-full px-3.5 py-2.5 text-xs font-mono border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none">
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Esta clave se guarda de forma segura en el archivo <code>.env</code> de tu servidor y habilita el escaneo automático con Visión AI.
                    </p>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalApiKey()" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl">Cancelar</button>
                    <button type="submit" id="btnGuardarKey" class="px-5 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow">Guardar Clave API</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts JavaScript Integrados -->
    <script>
        function abrirModalEscaner() {
            document.getElementById('modalEscaner').classList.remove('hidden');
        }

        function cerrarModalEscaner() {
            detenerCamara();
            document.getElementById('modalEscaner').classList.add('hidden');
        }

        let videoStream = null;

        async function iniciarCamara() {
            const contenedor = document.getElementById('contenedorCamara');
            const opciones = document.getElementById('opcionesCaptura');
            const video = document.getElementById('webcamVideo');

            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' },
                    audio: false
                });
                video.srcObject = videoStream;
                opciones.classList.add('hidden');
                contenedor.classList.remove('hidden');
            } catch (err) {
                console.error("Error al acceder a la cámara:", err);
                alert("No se pudo acceder a la cámara. Por favor selecciona una imagen de la galería.");
            }
        }

        function detenerCamara() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            document.getElementById('contenedorCamara').classList.add('hidden');
            document.getElementById('opcionesCaptura').classList.remove('hidden');
        }

        let ultimaImagenCapturada = null;

        function capturarDeCamara() {
            const video = document.getElementById('webcamVideo');
            const canvas = document.getElementById('webcamCanvas');
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const base64Image = canvas.toDataURL('image/jpeg', 0.85);
            ultimaImagenCapturada = base64Image;
            detenerCamara();
            enviarImagenParaAnalisis({ imagen_base64: base64Image }, false, base64Image);
        }

        function subirArchivoSeleccionado(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    const MAX_SIZE = 1200;
                    if (width > height) {
                        if (width > MAX_SIZE) {
                            height *= MAX_SIZE / width;
                            width = MAX_SIZE;
                        }
                    } else {
                        if (height > MAX_SIZE) {
                            width *= MAX_SIZE / height;
                            height = MAX_SIZE;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    const resizedBase64 = canvas.toDataURL('image/jpeg', 0.85);
                    ultimaImagenCapturada = resizedBase64;
                    enviarImagenParaAnalisis({ imagen_base64: resizedBase64 }, false, resizedBase64);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function extraerDatosOCRDeEtiqueta(texto) {
            console.log("Texto completo reconocido por OCR:", texto);
            const res = {};

            const mEstilo = texto.match(/ESTILO\s*[:\.-]?\s*([A-Za-z0-9\-]+)/i);
            const mM = texto.match(/\b(M-\d+[A-Za-z0-9\-]*)\b/i);
            const mMod = texto.match(/MOD(?:ELO)?\s*[:\.-]?\s*([A-Za-z0-9\-]+)/i);

            if (mEstilo && mEstilo[1]) {
                res.estilo = mEstilo[1].trim().toUpperCase();
            } else if (mM && mM[1]) {
                res.estilo = mM[1].trim().toUpperCase();
            } else if (mMod && mMod[1]) {
                res.estilo = mMod[1].trim().toUpperCase();
            }

            const mDecimal = texto.match(/\b(\d{2}\.[05])\b/);
            const mCualquierDec = texto.match(/\b(\d{2}\.\d)\b/);
            if (mDecimal) {
                res.numero = mDecimal[1];
            } else if (mCualquierDec) {
                res.numero = mCualquierDec[1];
            }

            const listaMateriales = [
                { key: 'CHAROL', val: 'Charol' },
                { key: 'SINTETICO', val: 'Sintético' },
                { key: 'SINTÉTICO', val: 'Sintético' },
                { key: 'PIEL', val: 'Piel' },
                { key: 'CUERO', val: 'Cuero' },
                { key: 'GAMUZA', val: 'Gamuza' },
                { key: 'TEXTIL', val: 'Textil' },
                { key: 'MALLA', val: 'Malla' },
                { key: 'LONA', val: 'Lona' }
            ];
            for (const mat of listaMateriales) {
                if (new RegExp('\\b' + mat.key + '\\b', 'i').test(texto)) {
                    res.material = mat.val;
                    break;
                }
            }

            const listaColores = [
                { key: 'NEGRO', val: 'Negro' },
                { key: 'BLANCO', val: 'Blanco' },
                { key: 'CAFE', val: 'Café' },
                { key: 'CAFÉ', val: 'Café' },
                { key: 'MARRON', val: 'Marrón' },
                { key: 'MARRÓN', val: 'Marrón' },
                { key: 'AZUL', val: 'Azul' }
            ];
            for (const col of listaColores) {
                if (new RegExp('\\b' + col.key + '\\b', 'i').test(texto)) {
                    res.color = col.val;
                    break;
                }
            }

            return res;
        }

        // Envío AJAX al backend para IA Gemini Vision
        function enviarImagenParaAnalisis(payload, isFormData = false, imageSrcForOCR = null) {
            document.getElementById('loaderIA').classList.remove('hidden');
            document.getElementById('opcionesCaptura').classList.add('hidden');

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let options = {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            };

            if (isFormData) {
                options.body = payload;
            } else {
                options.headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(payload);
            }

            fetch("{{ route('admin.zapatos.analizar') }}", options)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('loaderIA').classList.add('hidden');
                    if (data.success) {
                        cerrarModalEscaner();
                        mostrarModalConfirmacion(data);

                        if (imageSrcForOCR && typeof Tesseract !== 'undefined' && (!data.estilo || !data.numero)) {
                            Tesseract.recognize(imageSrcForOCR, 'eng')
                                .then(result => {
                                    if (result && result.data && result.data.text) {
                                        const ocr = extraerDatosOCRDeEtiqueta(result.data.text);
                                        if (ocr.estilo && !document.getElementById('confEstilo').value) document.getElementById('confEstilo').value = ocr.estilo;
                                        if (ocr.numero && !document.getElementById('confNumero').value) document.getElementById('confNumero').value = ocr.numero;
                                        if (ocr.color && !document.getElementById('confColor').value) document.getElementById('confColor').value = ocr.color;
                                        if (ocr.material && !document.getElementById('confMaterial').value) document.getElementById('confMaterial').value = ocr.material;
                                    }
                                }).catch(e => console.warn("OCR fallback:", e));
                        }
                    } else {
                        alert(data.error || 'Error al analizar la imagen del zapato.');
                        document.getElementById('opcionesCaptura').classList.remove('hidden');
                    }
                })
                .catch(err => {
                    document.getElementById('loaderIA').classList.add('hidden');
                    document.getElementById('opcionesCaptura').classList.remove('hidden');
                    console.error(err);
                    alert('Ocurrió un error al procesar la foto con Inteligencia Artificial.');
                });
        }

        function abrirCapturaManual() {
            cerrarModalEscaner();
            mostrarModalConfirmacion({
                imagen_url: "{{ asset('storage/zapatos/default.png') }}",
                imagen_path: 'storage/zapatos/default.png',
                estilo: '',
                numero: '25.0',
                color: '',
                material: '',
                bordado: ''
            });
        }

        function mostrarModalConfirmacion(data) {
            document.getElementById('confImagenPreview').src = data.imagen_url;
            document.getElementById('confImagenPath').value = data.imagen_path;
            document.getElementById('confEstilo').value = data.estilo || '';
            document.getElementById('confNumero').value = data.numero || '';
            document.getElementById('confColor').value = data.color || '';
            document.getElementById('confMaterial').value = data.material || '';
            document.getElementById('confBordado').value = data.bordado || '';
            document.getElementById('confCantidad').value = 1;
            document.getElementById('confPrecio').value = '';

            document.getElementById('modalConfirmacion').classList.remove('hidden');
        }

        function cerrarModalConfirmacion() {
            document.getElementById('modalConfirmacion').classList.add('hidden');
        }

        function guardarZapatoInventario(e) {
            e.preventDefault();
            const btn = document.getElementById('btnGuardarFinal');
            btn.disabled = true;
            btn.innerHTML = '<span>Guardando...</span>';

            const form = document.getElementById('formGuardarZapato');
            const formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('admin.zapatos.guardar') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => {
                        throw new Error(err.error || err.message || 'Error al procesar la solicitud');
                    });
                }
                return res.json();
            })
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<span>Guardar en Inventario</span>';
                if (data.success) {
                    cerrarModalConfirmacion();
                    if (data.mensaje) {
                        alert(data.mensaje);
                    }
                    window.location.reload();
                } else {
                    alert(data.error || 'Error al guardar en inventario.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<span>Guardar en Inventario</span>';
                console.error("Error guardando zapato:", err);
                alert("No se pudo guardar: " + err.message);
            });
        }

        function abrirModalEditar(zapato) {
            document.getElementById('formActualizarZapato').action = "/admin/zapatos/actualizar/" + zapato.id;
            document.getElementById('editEstilo').value = zapato.estilo;
            document.getElementById('editNumero').value = zapato.numero;
            document.getElementById('editColor').value = zapato.color;
            document.getElementById('editMaterial').value = zapato.material;
            document.getElementById('editBordado').value = zapato.bordado || '';
            document.getElementById('editCantidad').value = zapato.cantidad;
            document.getElementById('editPrecio').value = zapato.precio;

            document.getElementById('modalEditar').classList.remove('hidden');
        }

        function cerrarModalEditar() {
            document.getElementById('modalEditar').classList.add('hidden');
        }

        function abrirModalApiKey() {
            document.getElementById('modalApiKey').classList.remove('hidden');
        }

        function cerrarModalApiKey() {
            document.getElementById('modalApiKey').classList.add('hidden');
        }

        function guardarApiKeyGemini(e) {
            e.preventDefault();
            const key = document.getElementById('inputApiKey').value;
            const btn = document.getElementById('btnGuardarKey');
            btn.disabled = true;
            btn.innerText = 'Guardando...';

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('admin.zapatos.apikey') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ gemini_api_key: key })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerText = 'Guardar Clave API';
                if (data.success) {
                    alert(data.mensaje);
                    cerrarModalApiKey();
                } else {
                    alert('Error al guardar la clave API.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerText = 'Guardar Clave API';
                console.error(err);
                alert('Ocurrió un error al guardar la clave API.');
            });
        }
    </script>
@endsection
