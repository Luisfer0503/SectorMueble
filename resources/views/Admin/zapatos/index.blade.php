@extends('layouts.admin')

@section('contenido')
    <!-- Meta CSRF para peticiones AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Engine Tesseract OCR para lectura local inmediata de etiquetas de fotos -->
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2 bg-amber-100 text-amber-900 rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <div>
                    <h1 class="serif-title text-2xl md:text-3xl font-bold text-zinc-950">Inventario de Zapatos (Escáner IA)</h1>
                    <p class="text-zinc-500 text-xs md:text-sm mt-0.5">Captura la foto de un calzado para extraer únicamente: <strong>Estilo</strong>, <strong>Número</strong>, <strong>Color</strong> y <strong>Material</strong>, e ingresar su <strong>Cantidad</strong> y <strong>Precio</strong>.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button onclick="abrirModalApiKey()" class="inline-flex items-center justify-center px-3.5 py-3 bg-zinc-800 hover:bg-zinc-900 text-amber-400 font-semibold text-xs md:text-sm rounded-lg shadow-sm transition-all space-x-2 border border-zinc-700" title="Configurar Clave API de Inteligencia Artificial">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span>Clave API IA</span>
            </button>

            <button onclick="abrirCapturaManual()" class="inline-flex items-center justify-center px-4 py-3 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold text-xs md:text-sm rounded-lg border border-zinc-300 shadow-sm transition-all space-x-2">
                <svg class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Captura Manual</span>
            </button>

            <button onclick="abrirModalEscaner()" class="inline-flex items-center justify-center px-5 py-3 bg-amber-850 hover:bg-amber-900 text-white font-semibold text-xs md:text-sm rounded-lg shadow-md hover:shadow-lg transition-all space-x-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9"/>
                </svg>
                <span>Tomar Foto / Escanear Zapato</span>
            </button>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Modelos Registrados</span>
                <span class="text-2xl font-bold text-zinc-900 block mt-1 font-sans">{{ $totalModelos }}</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-800 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Total Pares en Stock</span>
                <span class="text-2xl font-bold text-amber-900 block mt-1 font-sans">{{ number_format($totalPares) }}</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Valor Total Inventario</span>
                <span class="text-2xl font-bold text-zinc-950 block mt-1 font-sans">$ {{ number_format($valorTotalInventario, 2, '.', ',') }}</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-800 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Search & Inventory Section -->
    <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-zinc-150 flex flex-col sm:flex-row items-center justify-between gap-4">
            <h2 class="serif-title text-lg font-bold text-zinc-950">Catálogo de Zapatos Escaneados</h2>
            
            <form action="{{ route('admin.zapatos') }}" method="GET" class="w-full sm:w-80 flex items-center space-x-2">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por estilo, talla, color o material..." class="w-full pl-9 pr-4 py-2 text-xs border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                    <svg class="w-4 h-4 text-zinc-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.zapatos') }}" class="text-xs text-zinc-500 hover:text-zinc-800 font-semibold underline">Limpiar</a>
                @endif
            </form>
        </div>

        @if($zapatos->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-20 h-20 bg-amber-50 text-amber-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    </svg>
                </div>
                <h3 class="serif-title text-lg font-bold text-zinc-900">No hay zapatos registrados en inventario</h3>
                <p class="text-zinc-500 text-xs mt-1 max-w-md mx-auto">Toma una foto de tu calzado o sube una imagen desde tu dispositivo para que la Inteligencia Artificial analice sus atributos automáticamente.</p>
                <button onclick="abrirModalEscaner()" class="mt-5 inline-flex items-center px-4 py-2 bg-amber-850 text-white font-semibold text-xs rounded-lg hover:bg-amber-900 transition-colors space-x-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Escanear Primer Zapato</span>
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50 border-b border-zinc-200 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Calzado</th>
                            <th class="py-3 px-4">Estilo</th>
                            <th class="py-3 px-4">Número / Talla</th>
                            <th class="py-3 px-4">Color</th>
                            <th class="py-3 px-4">Material</th>
                            <th class="py-3 px-4 text-center">Stock (Cantidad)</th>
                            <th class="py-3 px-4 text-right">Precio Unitario</th>
                            <th class="py-3 px-4 text-right">Subtotal Lote</th>
                            <th class="py-3 px-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-150 text-xs">
                        @foreach($zapatos as $zapato)
                            <tr class="hover:bg-amber-50/40 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $zapato->imagen_url }}" alt="{{ $zapato->estilo }}" class="w-12 h-12 object-cover rounded-lg border border-zinc-200 shadow-sm bg-zinc-100 flex-shrink-0">
                                        <div>
                                            <span class="font-bold text-zinc-900 block">Zapato #{{ $zapato->id }}</span>
                                            <span class="text-[10px] text-zinc-400 block">{{ $zapato->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-zinc-900">{{ $zapato->estilo }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-amber-900 bg-amber-50/60 px-2 py-1 rounded inline-block my-3">Talla: {{ $zapato->numero }}</td>
                                <td class="py-3 px-4 text-zinc-700">{{ $zapato->color }}</td>
                                <td class="py-3 px-4 text-zinc-700">{{ $zapato->material }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if($zapato->cantidad > 5)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">{{ $zapato->cantidad }} pares</span>
                                    @elseif($zapato->cantidad > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">{{ $zapato->cantidad }} pares</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">Agotado</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-zinc-900 font-sans">$ {{ number_format($zapato->precio, 2, '.', ',') }}</td>
                                <td class="py-3 px-4 text-right font-bold text-amber-900 font-sans">$ {{ number_format($zapato->valorTotal(), 2, '.', ',') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="abrirModalEditar({{ json_encode($zapato) }})" class="p-1.5 text-zinc-500 hover:text-amber-850 hover:bg-zinc-100 rounded transition-colors" title="Editar datos o stock">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.zapatos.eliminar', $zapato->id) }}" onclick="return confirm('¿Estás seguro de eliminar este zapato del inventario?')" class="p-1.5 text-zinc-500 hover:text-rose-600 hover:bg-zinc-100 rounded transition-colors" title="Eliminar zapato">
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

            <!-- Pagination -->
            <div class="p-4 border-t border-zinc-200">
                {{ $zapatos->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- MODAL 1: ESCÁNER / CAPTURA DE FOTO DE ZAPATO -->
    <!-- ========================================== -->
    <div id="modalEscaner" class="fixed inset-0 z-50 hidden bg-zinc-950/70 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative transition-all border border-zinc-100">
            <button onclick="cerrarModalEscaner()" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-700 p-1 rounded-full hover:bg-zinc-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="text-center mb-5">
                <div class="w-12 h-12 bg-amber-100 text-amber-900 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    </svg>
                </div>
                <h3 class="serif-title text-xl font-bold text-zinc-950">Tomar Foto del Zapato</h3>
                <p class="text-xs text-zinc-500 mt-1">Usa la cámara en vivo de tu dispositivo o selecciona una fotografía guardada.</p>
            </div>

            <!-- Opción 1: Visor de Cámara WebCam -->
            <div id="contenedorCamara" class="hidden mb-4">
                <div class="relative bg-black rounded-xl overflow-hidden aspect-video flex items-center justify-center border border-zinc-800">
                    <video id="webcamVideo" autoplay playsinline class="w-full h-full object-cover"></video>
                    <canvas id="webcamCanvas" class="hidden"></canvas>
                </div>
                <div class="flex items-center justify-center gap-3 mt-3">
                    <button type="button" onclick="capturarDeCamara()" class="px-5 py-2.5 bg-amber-850 hover:bg-amber-900 text-white text-xs font-bold rounded-lg shadow flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                        <span>Capturar Foto Ahora</span>
                    </button>
                    <button type="button" onclick="detenerCamara()" class="px-4 py-2.5 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 text-xs font-semibold rounded-lg">
                        Cancelar Cámara
                    </button>
                </div>
            </div>

            <!-- Opción 2: Botones de Selección -->
            <div id="opcionesCaptura" class="space-y-3">
                <button type="button" onclick="iniciarCamara()" class="w-full py-3.5 px-4 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs rounded-xl shadow flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Abrir Cámara en Vivo (WebCam / Celular)</span>
                </button>

                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-zinc-200"></div>
                    <span class="flex-shrink mx-3 text-[10px] text-zinc-400 font-bold uppercase tracking-wider">o bien</span>
                    <div class="flex-grow border-t border-zinc-200"></div>
                </div>

                <label class="w-full py-3.5 px-4 bg-amber-50 hover:bg-amber-100 border-2 border-dashed border-amber-300 text-amber-950 font-semibold text-xs rounded-xl flex items-center justify-center space-x-2 cursor-pointer transition-colors">
                    <svg class="w-5 h-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Seleccionar / Subir Imagen de Galería</span>
                    <input type="file" id="inputFotoArchivo" accept="image/*" capture="environment" onchange="subirArchivoSeleccionado(event)" class="hidden">
                </label>

                <button type="button" onclick="abrirCapturaManual()" class="w-full py-3 px-4 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold text-xs rounded-xl border border-zinc-300 flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Capturar Datos Manualmente (Boton Manual)</span>
                </button>
            </div>

            <!-- Spinner / Overlay de Procesamiento con IA -->
            <div id="loaderIA" class="hidden mt-6 text-center py-6 bg-amber-50/70 border border-amber-200 rounded-xl">
                <div class="inline-block animate-spin w-9 h-9 border-4 border-amber-600 border-t-transparent rounded-full mb-3"></div>
                <h4 class="font-bold text-amber-950 text-sm">Analizando Zapato con Inteligencia Artificial...</h4>
                <p class="text-xs text-amber-800 mt-1">Reconociendo Estilo, Número, Color y Material...</p>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 2: CONFIRMACIÓN DE DATOS, CANTIDAD Y PRECIO -->
    <!-- ========================================== -->
    <div id="modalConfirmacion" class="fixed inset-0 z-50 hidden bg-zinc-950/75 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative border border-zinc-100 my-8">
            <button onclick="cerrarModalConfirmacion()" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-700 p-1 rounded-full hover:bg-zinc-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex items-center space-x-3 mb-6">
                <span class="p-2 bg-emerald-100 text-emerald-800 rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="serif-title text-xl font-bold text-zinc-950">Confirmar e Ingresar a Inventario</h3>
                    <p class="text-xs text-zinc-500">Verifica los datos reconocidos e ingresa la cantidad y el precio del zapato.</p>
                </div>
            </div>

            <form id="formGuardarZapato" onsubmit="guardarZapatoInventario(event)">
                <input type="hidden" id="confImagenPath" name="imagen_path">

                <!-- Preview de Foto + Atributos IA -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 bg-zinc-50 p-4 rounded-xl border border-zinc-200">
                    <div class="sm:col-span-1">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Foto Capturada</span>
                        <img id="confImagenPreview" src="" alt="Vista Previa" class="w-full h-32 object-cover rounded-lg border border-zinc-300 shadow-sm">
                    </div>

                    <div class="sm:col-span-2 space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-600 uppercase">Estilo (ej. Estilo: 4501)</label>
                            <input type="text" id="confEstilo" name="estilo" required placeholder="ej. Estilo: 4501, Deportivo..." class="w-full mt-1 px-3 py-1.5 text-xs font-semibold bg-white border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-zinc-600 uppercase">Número / Talla (ej. 20.0, 25.5)</label>
                            <input type="text" id="confNumero" name="numero" list="listaTallasDecimales" required placeholder="ej. 20.0, 25.5, 27.0" class="w-full mt-1 px-3 py-1.5 text-xs font-semibold bg-white border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-2">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-600 uppercase">Color</label>
                        <input type="text" id="confColor" name="color" required placeholder="ej. Negro, Blanco, Café" class="w-full mt-1 px-3 py-2 text-xs font-semibold bg-white border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-zinc-600 uppercase">Material (Seguido del Color)</label>
                        <input type="text" id="confMaterial" name="material" required placeholder="ej. Piel, Gamuza, Sintético" class="w-full mt-1 px-3 py-2 text-xs font-semibold bg-white border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>
                </div>
                <p class="text-[10px] text-zinc-400 mb-6 italic">* Nota: Si en la foto la etiqueta dice p. ej. "Negro Piel", el sistema separa automáticamente Color: Negro y Material: Piel.</p>

                <!-- Campos de Cantidad y Precio (Destacados) -->
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-amber-950 uppercase tracking-wider">
                            Cantidad (Stock) <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative mt-1">
                            <input type="number" id="confCantidad" name="cantidad" min="1" value="1" required class="w-full px-3 py-2 text-sm font-bold bg-white border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-600 outline-none text-zinc-900">
                            <span class="absolute right-3 top-2.5 text-xs text-zinc-400 font-semibold">pares</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-amber-950 uppercase tracking-wider">
                            Precio ($) <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-zinc-500">$</span>
                            <input type="number" id="confPrecio" name="precio" step="0.01" min="0" placeholder="850.00" required class="w-full pl-7 pr-3 py-2 text-sm font-bold bg-white border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-600 outline-none text-zinc-900">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarModalConfirmacion()" class="px-4 py-2.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-zinc-100 hover:bg-zinc-200 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" id="btnGuardarFinal" class="px-6 py-2.5 bg-amber-850 hover:bg-amber-900 text-white text-xs font-bold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    <div id="modalEditar" class="fixed inset-0 z-50 hidden bg-zinc-950/75 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative border border-zinc-100">
            <button onclick="cerrarModalEditar()" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-700 p-1 rounded-full hover:bg-zinc-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="serif-title text-xl font-bold text-zinc-950 mb-4">Editar Calzado de Inventario</h3>

            <form id="formActualizarZapato" method="POST" action="">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase">Estilo</label>
                        <input type="text" id="editEstilo" name="estilo" required class="w-full mt-1 px-3 py-2 text-xs border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase">Número / Talla (ej. 20.0, 25.5)</label>
                            <input type="text" id="editNumero" name="numero" list="listaTallasDecimales" required class="w-full mt-1 px-3 py-2 text-xs border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase">Color</label>
                            <input type="text" id="editColor" name="color" required class="w-full mt-1 px-3 py-2 text-xs border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase">Material</label>
                        <input type="text" id="editMaterial" name="material" required class="w-full mt-1 px-3 py-2 text-xs border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3 p-3 bg-zinc-50 border border-zinc-200 rounded-lg">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase">Cantidad (Stock)</label>
                            <input type="number" id="editCantidad" name="cantidad" min="0" required class="w-full mt-1 px-3 py-2 text-xs font-bold border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase">Precio ($)</label>
                            <input type="number" id="editPrecio" name="precio" step="0.01" min="0" required class="w-full mt-1 px-3 py-2 text-xs font-bold border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 text-xs font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-lg">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-amber-850 hover:bg-amber-900 text-white text-xs font-bold rounded-lg shadow">Guardar Cambios</button>
                </div>
            </form>
    <!-- Modal Configuración Clave API Gemini -->
    <div id="modalApiKey" class="fixed inset-0 z-50 bg-zinc-950/70 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-zinc-200">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-100">
                <div class="flex items-center space-x-3">
                    <span class="p-2 bg-amber-100 text-amber-900 rounded-lg">
                        <svg class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="serif-title text-lg font-bold text-zinc-950">Clave API de Inteligencia Artificial</h3>
                        <p class="text-xs text-zinc-500">Google Gemini 1.5 Flash Vision (100% Gratuita)</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalApiKey()" class="text-zinc-400 hover:text-zinc-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="formApiKey" onsubmit="guardarApiKeyGemini(event)">
                <div class="space-y-3 mb-6">
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Ingresa tu clave API gratuita de Google Gemini para que el escáner analice las fotografías de tus etiquetas con máxima precisión visual.
                    </p>
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase mb-1">Clave GEMINI_API_KEY</label>
                        <input type="text" id="inputApiKey" name="gemini_api_key" placeholder="AIzaSy..." required class="w-full px-3 py-2 text-xs font-mono border border-zinc-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>
                    <div class="text-[11px] text-amber-800 bg-amber-50 p-2.5 rounded-lg border border-amber-200">
                        💡 Puedes obtener tu clave gratuita en segundos en: <a href="https://aistudio.google.com/app/apikey" target="_blank" class="underline font-bold text-amber-900">aistudio.google.com</a>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalApiKey()" class="px-4 py-2 text-xs font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-lg">Cancelar</button>
                    <button type="submit" id="btnGuardarKey" class="px-5 py-2 bg-amber-850 hover:bg-amber-900 text-white text-xs font-bold rounded-lg shadow">Guardar Clave API</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts de Cámara e Interacción IA -->
    <script>
        let videoStream = null;

        function abrirModalEscaner() {
            document.getElementById('modalEscaner').classList.remove('hidden');
            document.getElementById('opcionesCaptura').classList.remove('hidden');
            document.getElementById('contenedorCamara').classList.add('hidden');
            document.getElementById('loaderIA').classList.add('hidden');
        }

        function cerrarModalEscaner() {
            detenerCamara();
            document.getElementById('modalEscaner').classList.add('hidden');
        }

        // WebCam Stream Handling
        async function iniciarCamara() {
            try {
                const constraints = {
                    video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
                };
                videoStream = await navigator.mediaDevices.getUserMedia(constraints);
                const videoElement = document.getElementById('webcamVideo');
                videoElement.srcObject = videoStream;

                document.getElementById('opcionesCaptura').classList.add('hidden');
                document.getElementById('contenedorCamara').classList.remove('hidden');
            } catch (err) {
                alert('No se pudo acceder a la cámara. Asegúrate de otorgar los permisos en el navegador o bien sube una foto desde tu galería.');
                console.error(err);
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

            const formData = new FormData();
            formData.append('imagen_archivo', file);

            const reader = new FileReader();
            reader.onload = function(e) {
                ultimaImagenCapturada = e.target.result;
                enviarImagenParaAnalisis(formData, true, e.target.result);
            };
            reader.readAsDataURL(file);
        }

        function extraerDatosOCRDeEtiqueta(texto) {
            console.log("Texto completo reconocido por OCR:", texto);
            const res = {};

            // 1. Estilo: Busca "ESTILO: 1124" o "M-631" o "MOD: 405"
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

            // 2. Número / Talla (ej. 21.0, 22.5, 25.0)
            const mDecimal = texto.match(/\b(\d{2}\.[05])\b/);
            const mCualquierDec = texto.match(/\b(\d{2}\.\d)\b/);
            if (mDecimal) {
                res.numero = mDecimal[1];
            } else if (mCualquierDec) {
                res.numero = mCualquierDec[1];
            }

            // 3. Material
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

            // 4. Color
            const listaColores = [
                { key: 'NEGRO', val: 'Negro' },
                { key: 'BLANCO', val: 'Blanco' },
                { key: 'CAFE', val: 'Café' },
                { key: 'CAFÉ', val: 'Café' },
                { key: 'MARRON', val: 'Marrón' },
                { key: 'MARRÓN', val: 'Marrón' },
                { key: 'AZUL', val: 'Azul' },
                { key: 'ROSA', val: 'Rosa' },
                { key: 'VINO', val: 'Vino' },
                { key: 'ROJO', val: 'Rojo' },
                { key: 'MIEL', val: 'Miel' },
                { key: 'GRIS', val: 'Gris' }
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

            // Ejecutar análisis directo con Gemini Vision AI (gemini-3.6-flash)
            fetch("{{ route('admin.zapatos.analizar') }}", options)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('loaderIA').classList.add('hidden');
                    if (data.success) {
                        cerrarModalEscaner();
                        mostrarModalConfirmacion(data);

                        // Si la IA requiere complemento y Tesseract está disponible
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
                material: ''
            });
        }

        // Modal de Confirmación
        function mostrarModalConfirmacion(data) {
            document.getElementById('confImagenPreview').src = data.imagen_url;
            document.getElementById('confImagenPath').value = data.imagen_path;
            document.getElementById('confEstilo').value = data.estilo || '';
            document.getElementById('confNumero').value = data.numero || '';
            document.getElementById('confColor').value = data.color || '';
            document.getElementById('confMaterial').value = data.material || '';
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
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<span>Guardar en Inventario</span>';
                if (data.success) {
                    cerrarModalConfirmacion();
                    window.location.reload();
                } else {
                    alert(data.error || 'Error al guardar en inventario.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<span>Guardar en Inventario</span>';
                console.error(err);
                alert('Error al guardar el zapato en la base de datos.');
            });
        }

        // Modal de Edición
        function abrirModalEditar(zapato) {
            document.getElementById('formActualizarZapato').action = "/admin/zapatos/actualizar/" + zapato.id;
            document.getElementById('editEstilo').value = zapato.estilo;
            document.getElementById('editNumero').value = zapato.numero;
            document.getElementById('editColor').value = zapato.color;
            document.getElementById('editMaterial').value = zapato.material;
            document.getElementById('editCantidad').value = zapato.cantidad;
            document.getElementById('editPrecio').value = zapato.precio;

            document.getElementById('modalEditar').classList.remove('hidden');
        }

        function cerrarModalEditar() {
            document.getElementById('modalEditar').classList.add('hidden');
        }

        // Modal de Clave API Gemini
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
                    alert('¡Clave de Inteligencia Artificial guardada con éxito! Ahora tus fotografías se analizarán con 100% de precisión visual.');
                    cerrarModalApiKey();
                } else {
                    alert('Error al guardar la clave API.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerText = 'Guardar Clave API';
                console.error(err);
                alert('Error al comunicar con el servidor.');
            });
        }
    </script>
@endsection
