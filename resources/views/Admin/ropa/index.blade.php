@extends('layouts.admin')

@section('contenido')
    <!-- Meta CSRF para peticiones AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Engine Tesseract OCR para lectura local de respaldo -->
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <!-- Contenedor Principal -->
    <div class="space-y-8">
        
        <!-- Header de Control de Inventario de Ropa -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-slate-900 text-amber-400 rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3l-4 4-4-4H3v6l3 3v9h12v-9l3-3V3h-5z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Módulo de Gestión de Prendas de Vestir</span>
                    <h1 class="serif-title text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Inventario de Ropa (Escáner IA)</h1>
                    <p class="text-slate-500 text-xs md:text-sm mt-1">Escanea fotos de etiquetas de ropa o ingresa datos manualmente. Generación automática de Clave Alterna.</p>
                </div>
            </div>

            <!-- Botones Principales -->
            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2.5 w-full xl:w-auto">
                <!-- Botón Principal Héroe: Tomar Foto -->
                <button onclick="abrirModalEscaner()" class="col-span-2 sm:flex-initial inline-flex items-center justify-center px-5 py-3.5 bg-amber-950 hover:bg-amber-900 text-amber-100 font-extrabold text-xs md:text-sm rounded-xl shadow-md hover:shadow-lg border border-amber-800 transition-all space-x-2 group cursor-pointer active:scale-95">
                    <svg class="w-5 h-5 text-amber-400 group-hover:scale-110 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9"/>
                    </svg>
                    <span>📷 Tomar Foto / Escanear Ropa</span>
                </button>

                <!-- Botón Descargar Excel -->
                <a href="{{ route('admin.ropa.excel', ['categoria' => $categoriaActiva]) }}" class="inline-flex items-center justify-center px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs md:text-sm rounded-xl shadow-sm hover:shadow transition-all space-x-1.5 active:scale-95">
                    <svg class="w-4 h-4 text-emerald-100 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>📥 Descargar Excel</span>
                </a>

                <!-- Botón Captura Manual -->
                <button onclick="abrirCapturaManual()" class="inline-flex items-center justify-center px-4 py-3 bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs md:text-sm rounded-xl border border-slate-300 shadow-sm transition-all space-x-1.5 active:scale-95">
                    <svg class="w-4 h-4 text-slate-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>✍️ Captura Manual</span>
                </button>

                <!-- Botón Clave API IA -->
                <button onclick="abrirModalApiKey()" class="col-span-2 sm:col-span-1 inline-flex items-center justify-center px-3.5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs md:text-sm rounded-xl border border-slate-300 transition-all space-x-1.5 active:scale-95" title="Configurar Clave API de Gemini Vision">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Categorías de Ropa -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2 overflow-x-auto pb-2 xl:pb-0 scrollbar-thin">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 whitespace-nowrap flex items-center space-x-1 mr-1">
                    <svg class="w-4 h-4 text-amber-600 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7M3 7h2v10H3z"/>
                    </svg>
                    <span>Categorías:</span>
                </span>
                
                @foreach($categorias as $cat)
                    @php
                        $esActiva = ($categoriaActiva && strtoupper(trim($cat)) === strtoupper(trim($categoriaActiva)));
                    @endphp
                    <a href="{{ route('admin.ropa', ['categoria' => $cat]) }}" 
                       class="px-4 py-2 rounded-xl text-xs md:text-sm font-bold transition-all whitespace-nowrap flex items-center space-x-1.5 {{ $esActiva ? 'bg-amber-950 text-amber-300 shadow-md ring-2 ring-amber-800' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        <span>{{ $cat }}</span>
                        @if($esActiva)
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse" title="Categoría abierta"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Botones de Acción de Categoría -->
            <div class="flex items-center space-x-2 shrink-0">
                @if($categoriaActiva)
                    <a href="{{ route('admin.ropa', ['categoria' => '']) }}" 
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-800 font-extrabold text-xs rounded-xl border border-rose-300 shadow-sm transition-all space-x-1.5 whitespace-nowrap cursor-pointer"
                       title="Cerrar la categoría abierta actual">
                        <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>✖ Cerrar Categoría</span>
                    </a>
                @else
                    <!-- Selector Rápido para Reabrir Categoría -->
                    <div class="flex items-center space-x-1.5">
                        <select onchange="if(this.value) window.location.href=this.value;" class="bg-amber-100 text-amber-950 border border-amber-300 text-xs font-black rounded-xl px-3 py-2 focus:ring-2 focus:ring-amber-500">
                            <option value="">📂 Reabrir Categoría...</option>
                            @foreach($categorias as $catOpt)
                                <option value="{{ route('admin.ropa', ['categoria' => $catOpt]) }}">Reabrir: {{ $catOpt }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button onclick="abrirModalNuevaCategoria()" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-black text-white font-extrabold text-xs rounded-xl shadow transition-all space-x-2 whitespace-nowrap cursor-pointer">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Nueva Categoría</span>
                </button>
            </div>
        </div>

        @if(!$categoriaActiva)
            <div class="bg-amber-50/80 border-2 border-dashed border-amber-300 rounded-3xl p-6 sm:p-8 text-center my-6 shadow-sm">
                <div class="w-16 h-16 bg-amber-100 text-amber-950 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner border border-amber-300">
                    <svg class="w-8 h-8 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="serif-title text-xl font-black text-slate-900">Categoría Cerrada</h3>
                <p class="text-slate-600 text-xs md:text-sm max-w-lg mx-auto mt-1">
                    Las categorías están cerradas. Haz clic en <strong>"📂 Reabrir Categoría"</strong> o selecciona una categoría a continuación para volver a abrirla y gestionar su inventario.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6 text-left">
                    @foreach($resumenCategorias as $catKey => $resCat)
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-mono font-black text-amber-950 bg-amber-100 px-3 py-1 rounded-lg border border-amber-300 text-xs">
                                        {{ $resCat['nombre'] }}
                                    </span>
                                    <span class="text-[10px] uppercase font-bold text-slate-400">Cerrada</span>
                                </div>
                                <div class="space-y-1 text-xs text-slate-600 mt-3">
                                    <p class="flex justify-between"><span>Modelos/Items:</span> <strong class="text-slate-900">{{ $resCat['modelos'] }}</strong></p>
                                    <p class="flex justify-between"><span>Total Prendas (Exist.):</span> <strong class="text-emerald-700">{{ number_format($resCat['prendas']) }}</strong></p>
                                    <p class="flex justify-between"><span>Valor Total:</span> <strong class="text-slate-900">$ {{ number_format($resCat['valor'], 2, '.', ',') }}</strong></p>
                                </div>
                            </div>
                            <a href="{{ route('admin.ropa', ['categoria' => $resCat['nombre']]) }}" class="mt-4 w-full py-2.5 bg-slate-900 hover:bg-black text-amber-300 text-xs font-bold rounded-xl text-center shadow transition-all block">
                                📂 Reabrir / Abrir esta Categoría
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Métricas de Inventario de la Categoría Abierta -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Modelos en {{ $categoriaActiva }}</span>
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
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Prendas (EXIST. {{ $categoriaActiva }})</span>
                        <span class="text-3xl font-extrabold text-emerald-700 block mt-1 font-sans">{{ number_format($totalPrendas) }}</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center border border-emerald-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Valor Inventario {{ $categoriaActiva }}</span>
                        <span class="text-3xl font-extrabold text-slate-900 block mt-1 font-sans">$ {{ number_format($valorTotalInventario, 2, '.', ',') }}</span>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-800 rounded-xl flex items-center justify-center border border-amber-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Barra Flotante de Acciones Masivas -->
            <div id="barraAccionesMasivas" class="hidden bg-slate-900 text-white p-4 sm:p-5 rounded-2xl shadow-xl border border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 mb-6 transition-all animate-fade-in">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-amber-400 text-amber-950 rounded-xl flex items-center justify-center font-black text-base shadow-inner shrink-0">
                        <span id="cantSeleccionadosCount">0</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-xs md:text-sm text-white">Prenda(s) seleccionada(s)</h4>
                        <p class="text-[11px] text-slate-400">Traspasa la ropa seleccionada a otra categoría existente. No se crearán duplicados.</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-64">
                        <select id="selectCategoriaDestinoMasivo" class="w-full bg-slate-800 border border-slate-700 text-amber-200 text-xs font-bold rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            <option value="">-- Seleccionar Categoría Destino --</option>
                            @foreach($categorias as $catOption)
                                @if(strtoupper(trim($catOption)) !== strtoupper(trim($categoriaActiva)))
                                    <option value="{{ $catOption }}">{{ $catOption }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <button type="button" onclick="ejecutarMoverRopaMasivo()" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>📦 Mover Ropa</span>
                    </button>

                    <button type="button" onclick="deseleccionarTodosRopa()" class="px-3 py-2.5 text-slate-400 hover:text-white text-xs font-bold transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>

            <!-- Tabla de Inventario de Ropa -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h2 class="serif-title text-xl font-bold text-slate-900">Catálogo de Ropa en <span class="text-amber-900 underline">{{ $categoriaActiva }}</span></h2>
                        <p class="text-slate-500 text-xs mt-0.5">Prendas registradas en la categoría seleccionada.</p>
                    </div>

                    <form action="{{ route('admin.ropa') }}" method="GET" class="w-full sm:w-80 flex items-center space-x-2">
                        <input type="hidden" name="categoria" value="{{ $categoriaActiva }}">
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar Marca, Talla, Estilo, Color, Barcode..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('admin.ropa', ['categoria' => $categoriaActiva]) }}" class="text-xs text-slate-500 hover:text-slate-800 font-bold underline">Limpiar</a>
                        @endif
                    </form>
                </div>

                @if($ropas->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="serif-title text-lg font-bold text-slate-900">No hay ropa registrada en la categoría '{{ $categoriaActiva }}'</h3>
                        <p class="text-slate-500 text-xs mt-1 max-w-sm mx-auto">Comienza escaneando con la cámara o ingresa las prendas manualmente.</p>
                        <button onclick="abrirModalEscaner()" class="mt-4 inline-flex items-center px-4 py-2.5 bg-amber-950 hover:bg-amber-900 text-amber-200 font-bold text-xs rounded-xl space-x-2 transition-all cursor-pointer">
                            <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            </svg>
                            <span>Escanear Primera Prenda</span>
                        </button>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-3.5 px-4 w-10 text-center">
                                        <input type="checkbox" id="selectAllRopa" onchange="toggleSelectAllRopa(this)" class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 cursor-pointer" title="Seleccionar toda la ropa visible">
                                    </th>
                                    <th class="py-3.5 px-3 w-12 text-center">#</th>
                                    <th class="py-3.5 px-4">Foto / Clave Alterna</th>
                                    <th class="py-3.5 px-4">Marca & Talla</th>
                                    <th class="py-3.5 px-4">Estilo / Color / Barcode</th>
                                    <th class="py-3.5 px-4 text-right">Precio Unit.</th>
                                    <th class="py-3.5 px-4 text-center">Stock (Exist.)</th>
                                    <th class="py-3.5 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @foreach($ropas as $ropa)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-4 px-4 text-center">
                                            <input type="checkbox" class="ropa-checkbox w-4 h-4 rounded text-amber-600 focus:ring-amber-500 cursor-pointer" value="{{ $ropa->id }}" onchange="actualizarBarraAccionMasivaRopa()">
                                        </td>
                                        <td class="py-4 px-3 text-center text-slate-400 font-mono text-[11px]">
                                            {{ $loop->iteration + ($ropas->currentPage() - 1) * $ropas->perPage() }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $ropa->imagen_url }}" alt="{{ $ropa->marca }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 shadow-sm bg-slate-100 flex-shrink-0">
                                                <div>
                                                    <span class="font-mono font-black text-amber-950 bg-amber-100 px-2 py-0.5 rounded border border-amber-300 text-[11px] block w-max">
                                                        {{ $ropa->clave_alterna }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 block mt-0.5">ID: #{{ $ropa->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-black text-slate-900 uppercase block text-xs tracking-tight">Marca: {{ $ropa->marca }}</span>
                                            <span class="font-bold text-amber-900 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 inline-block text-[11px] mt-1">
                                                Talla: {{ $ropa->talla }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex flex-wrap gap-1 text-[11px]">
                                                @if($ropa->estilo)
                                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">Estilo: <strong>{{ $ropa->estilo }}</strong></span>
                                                @endif
                                                @if($ropa->color)
                                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">Color: <strong>{{ $ropa->color }}</strong></span>
                                                @endif
                                                @if($ropa->art)
                                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">Art: <strong>{{ $ropa->art }}</strong></span>
                                                @endif
                                                @if($ropa->codigo_barras)
                                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200 font-mono">Barcode: <strong>{{ $ropa->codigo_barras }}</strong></span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-right font-extrabold text-slate-900 text-sm font-mono">
                                            $ {{ number_format($ropa->precio, 2, '.', ',') }}
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($ropa->cantidad > 5)
                                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">{{ $ropa->cantidad }}</span>
                                            @elseif($ropa->cantidad > 0)
                                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 border border-amber-300">{{ $ropa->cantidad }}</span>
                                            @else
                                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-rose-100 text-rose-900 border border-rose-300">Agotado</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex flex-wrap items-center justify-end gap-1.5 min-w-[220px]">
                                                <!-- Botón + Talla para este modelo -->
                                                <button onclick="abrirModalAgregarTallaRopa({{ json_encode($ropa) }})" class="px-2.5 py-2 bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-xs rounded-xl shadow-sm border border-amber-500 flex items-center space-x-1 whitespace-nowrap cursor-pointer transition-all active:scale-95" title="Agregar nueva talla (+ Talla) para este modelo">
                                                    <svg class="w-3.5 h-3.5 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    <span>+ Talla</span>
                                                </button>

                                                <!-- Duplicar prenda -->
                                                <button onclick="abrirModalDuplicarRopa({{ json_encode($ropa) }})" class="px-2.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-xl shadow-sm border border-indigo-500 flex items-center space-x-1 whitespace-nowrap cursor-pointer transition-all active:scale-95" title="Duplicar prenda (Copiar y modificar 1 o 2 datos)">
                                                    <svg class="w-3.5 h-3.5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span>📋 Duplicar</span>
                                                </button>

                                                <!-- Editar prenda -->
                                                <button onclick="abrirModalEditarRopa({{ json_encode($ropa) }})" class="px-2.5 py-2 bg-slate-800 hover:bg-black text-slate-100 font-extrabold text-xs rounded-xl shadow-sm border border-slate-700 flex items-center space-x-1 whitespace-nowrap cursor-pointer transition-all active:scale-95" title="Editar Prenda">
                                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    <span>✏️ Editar</span>
                                                </button>

                                                <!-- Eliminar prenda -->
                                                <a href="{{ route('admin.ropa.eliminar', $ropa->id) }}" onclick="return confirm('¿Seguro que deseas eliminar esta prenda del inventario?');" class="px-2.5 py-2 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white font-extrabold text-xs rounded-xl border border-rose-300 flex items-center space-x-1 whitespace-nowrap cursor-pointer transition-all active:scale-95" title="Eliminar Prenda">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>🗑️ Borrar</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-slate-100">
                        {{ $ropas->appends(['categoria' => $categoriaActiva, 'search' => request('search')])->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- ==================== MODALES Y SCRIPTS ==================== -->

    <!-- MODAL 1: ESCÁNER / FOTO DE PRENDA DE ROPA -->
    <div id="modalEscanerRopa" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
            <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-amber-400 text-amber-950 rounded-xl flex items-center justify-center font-bold">
                        📸
                    </div>
                    <div>
                        <h3 class="serif-title text-lg font-extrabold text-amber-300">Escáner Inteligente de Ropa (IA)</h3>
                        <p class="text-xs text-slate-400">Toma una foto de la etiqueta o prenda para extraer datos automáticamente.</p>
                    </div>
                </div>
                <button onclick="cerrarModalEscaner()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <div class="p-6 space-y-6 overflow-y-auto">
                <div class="bg-slate-100 p-4 rounded-2xl text-center border-2 border-dashed border-slate-300">
                    <!-- Vista previa de Cámara / Imagen subida -->
                    <div id="camaraContainerRopa" class="relative w-full aspect-video bg-black rounded-xl overflow-hidden shadow-inner flex items-center justify-center">
                        <video id="videoElementRopa" autoplay playsinline class="w-full h-full object-cover hidden"></video>
                        <canvas id="canvasElementRopa" class="hidden"></canvas>
                        <img id="imagePreviewRopa" class="w-full h-full object-contain hidden" alt="Vista previa prenda">
                        
                        <div id="placeholderCamaraRopa" class="text-slate-400 text-center p-4">
                            <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            </svg>
                            <span class="text-xs font-bold block">Inicia la Cámara o selecciona un archivo de imagen</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-3 mt-4">
                        <button type="button" onclick="iniciarCamaraRopa()" class="px-4 py-2.5 bg-slate-900 hover:bg-black text-amber-300 text-xs font-bold rounded-xl shadow transition-all cursor-pointer">
                            📷 Iniciar Cámara
                        </button>
                        <button type="button" id="btnCapturarRopa" onclick="capturarFotoRopa()" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black rounded-xl shadow transition-all hidden cursor-pointer">
                            🎯 Tomar Foto
                        </button>
                        <label class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold rounded-xl border border-slate-300 shadow-sm cursor-pointer transition-all">
                            📁 Subir Archivo
                            <input type="file" id="fileInputRopa" accept="image/*" class="hidden" onchange="cargarArchivoRopa(event)">
                        </label>
                    </div>
                </div>

                <!-- Indicador de Carga / Análisis IA -->
                <div id="loadingIaRopa" class="hidden bg-amber-50 border border-amber-200 p-4 rounded-2xl text-center space-y-2">
                    <div class="inline-block animate-spin w-8 h-8 border-4 border-amber-600 border-t-transparent rounded-full"></div>
                    <p class="text-xs font-extrabold text-amber-950">Inteligencia Artificial analizando etiqueta de ropa...</p>
                    <p class="text-[11px] text-amber-800">Extrayendo Marca, Talla, Estilo, Color, Código de Barras y Artículo.</p>
                </div>

                <!-- Formulario de Confirmación y Edición tras Análisis -->
                <form id="formGuardarRopaEscaneada" action="{{ route('admin.ropa.guardar') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="categoria" value="{{ $categoriaActiva }}">
                    <input type="hidden" id="escanerImagenPathRopa" name="imagen_path" value="">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-900 mb-1">
                                Marca <span class="text-rose-600">* (Obligatorio)</span>
                            </label>
                            <input type="text" id="escanerMarca" name="marca" required placeholder="Ej. NIKE, LEVI'S, ZARA" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Estilo (Opcional)</label>
                            <input type="text" id="escanerEstilo" name="estilo" placeholder="Ej. POLO, CASUAL, SLIM FIT" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-900 mb-1">
                                Color <span class="text-rose-600">* (Obligatorio)</span>
                            </label>
                            <input type="text" id="escanerColor" name="color" required placeholder="Ej. NEGRO, BLANCO, AZUL" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Código de Barras (Opcional)</label>
                            <input type="text" id="escanerCodigoBarras" name="codigo_barras" placeholder="Ej. 750123456789" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-800 focus:ring-2 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Art / Código Artículo (Opcional)</label>
                            <input type="text" id="escanerArt" name="art" placeholder="Ej. ART-102" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-900 mb-1">
                                Precio Unitario ($) <span class="text-rose-600">* (Obligatorio)</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" name="precio" required placeholder="0.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 font-mono focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <!-- Sección de Tallas Dinámicas (+ Talla) -->
                    <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-extrabold text-slate-900 block">Tallas y Cantidades (Existencias) <span class="text-rose-600">*</span></span>
                                <span class="text-[11px] text-slate-500">Puedes agregar múltiples tallas para esta misma prenda escaneada.</span>
                            </div>
                            <button type="button" onclick="agregarFilaTallaEscanerRopa()" class="px-3 py-1.5 bg-amber-200 hover:bg-amber-300 text-amber-950 font-black text-xs rounded-lg transition-all cursor-pointer shrink-0">
                                + Agregar Otra Talla
                            </button>
                        </div>

                        <div id="contenedorFilasTallasEscanerRopa" class="space-y-2">
                            <div class="flex items-center space-x-3 fila-talla-escaner-ropa">
                                <div class="flex-1">
                                    <input type="text" id="escanerTalla" name="tallas[0][talla]" required placeholder="Talla (Ej. CH, M, G, XL, 30, 32)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800 focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div class="w-32">
                                    <input type="number" name="tallas[0][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                                </div>
                                <button type="button" onclick="eliminarFilaTallaEscanerRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg hidden text-xs font-bold">
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" onclick="cerrarModalEscaner()" class="px-4 py-2.5 text-slate-600 text-xs font-bold hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                        <button type="submit" class="px-6 py-2.5 bg-amber-950 hover:bg-amber-900 text-amber-300 text-xs font-black rounded-xl shadow-md transition-all cursor-pointer">
                            💾 Guardar Prenda en {{ $categoriaActiva }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- MODAL 2: CAPTURA MANUAL (Soporta Múltiples Tallas + Talla) -->
    <div id="modalCapturaManualRopa" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
            <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-amber-400 text-amber-950 rounded-xl flex items-center justify-center font-bold">
                        ✍️
                    </div>
                    <div>
                        <h3 class="serif-title text-lg font-extrabold text-amber-300">Captura Manual de Ropa</h3>
                        <p class="text-xs text-slate-400">Ingresa los datos manualmente y agrega una o varias tallas a la vez.</p>
                    </div>
                </div>
                <button onclick="cerrarCapturaManual()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.ropa.guardar') }}" method="POST" class="p-6 space-y-6 overflow-y-auto">
                @csrf
                <input type="hidden" name="categoria" value="{{ $categoriaActiva }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Marca <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="text" name="marca" required placeholder="Ej. NIKE, LEVI'S, ZARA" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estilo (Opcional)</label>
                        <input type="text" name="estilo" placeholder="Ej. POLO, CASUAL, SLIM FIT" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Color <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="text" name="color" required placeholder="Ej. NEGRO, BLANCO" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Código de Barras (Opcional)</label>
                        <input type="text" name="codigo_barras" placeholder="Ej. 750123456789" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-800 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Art / Código Artículo (Opcional)</label>
                        <input type="text" name="art" placeholder="Ej. ART-102" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Precio Unitario ($) <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" name="precio" required placeholder="0.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 font-mono focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <!-- Sección de Tallas Dinámicas (+ Talla) -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-900">Tallas y Cantidades (Existencias) <span class="text-rose-600">*</span></span>
                        <button type="button" onclick="agregarFilaTallaManualRopa()" class="px-3 py-1.5 bg-amber-200 hover:bg-amber-300 text-amber-950 font-black text-xs rounded-lg transition-all cursor-pointer">
                            + Agregar Otra Talla
                        </button>
                    </div>

                    <div id="contenedorFilasTallasRopa" class="space-y-2">
                        <div class="flex items-center space-x-3 fila-talla-ropa">
                            <div class="flex-1">
                                <input type="text" name="tallas[0][talla]" required placeholder="Talla (Ej. CH, M, G, 32)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800">
                            </div>
                            <div class="w-32">
                                <input type="number" name="tallas[0][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                            </div>
                            <button type="button" onclick="eliminarFilaTallaManualRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg hidden text-xs font-bold">
                                &times;
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarCapturaManual()" class="px-4 py-2.5 text-slate-600 text-xs font-bold hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-black text-amber-300 text-xs font-black rounded-xl shadow-md transition-all cursor-pointer">
                        💾 Registrar Ropa en {{ $categoriaActiva }}
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL 3: AGREGAR TALLA (+ TALLA A MODELO EXISTENTE) -->
    <div id="modalAgregarTallaRopa" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-amber-400 text-amber-950 rounded-xl flex items-center justify-center font-bold">
                        ➕
                    </div>
                    <div>
                        <h3 class="serif-title text-base font-extrabold text-amber-300">Agregar Nueva Talla</h3>
                        <p class="text-[11px] text-slate-400">Añade stock de otra talla para la misma marca y modelo.</p>
                    </div>
                </div>
                <button onclick="cerrarModalAgregarTallaRopa()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.ropa.guardar') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="categoria" value="{{ $categoriaActiva }}">
                <input type="hidden" id="addTallaMarca" name="marca" value="">
                <input type="hidden" id="addTallaEstilo" name="estilo" value="">
                <input type="hidden" id="addTallaColor" name="color" value="">
                <input type="hidden" id="addTallaCodigo" name="codigo_barras" value="">
                <input type="hidden" id="addTallaArt" name="art" value="">
                <input type="hidden" id="addTallaPrecio" name="precio" value="">

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs space-y-1">
                    <p><strong>Marca:</strong> <span id="lblAddMarca" class="text-slate-900 font-bold"></span></p>
                    <p><strong>Estilo / Color:</strong> <span id="lblAddEstiloColor" class="text-slate-700"></span></p>
                    <p><strong>Precio Actual:</strong> $<span id="lblAddPrecio" class="font-mono text-slate-900 font-bold"></span></p>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-900 mb-1">
                        Nueva Talla <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="talla" required placeholder="Ej. CH, M, G, XL, 32" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Cantidad (Stock)</label>
                    <input type="number" name="cantidad" min="1" value="1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarModalAgregarTallaRopa()" class="px-4 py-2 text-slate-600 text-xs font-bold hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-amber-950 hover:bg-amber-900 text-amber-300 text-xs font-black rounded-xl shadow transition-all cursor-pointer">
                        💾 Guardar Nueva Talla
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL 4: EDITAR PRENDA DE ROPA -->
    <div id="modalEditarRopa" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-amber-400 text-amber-950 rounded-xl flex items-center justify-center font-bold">
                        ✏️
                    </div>
                    <div>
                        <h3 class="serif-title text-base font-extrabold text-amber-300">Editar Prenda</h3>
                        <p class="text-[11px] text-slate-400">Modifica la información o existencias del registro.</p>
                    </div>
                </div>
                <button onclick="cerrarModalEditarRopa()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form id="formEditarRopa" action="" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Marca <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="text" id="editRopaMarca" name="marca" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Talla <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="text" id="editRopaTalla" name="talla" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estilo (Opcional)</label>
                        <input type="text" id="editRopaEstilo" name="estilo" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Color <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="text" id="editRopaColor" name="color" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Código de Barras (Opcional)</label>
                        <input type="text" id="editRopaCodigo" name="codigo_barras" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Art (Opcional)</label>
                        <input type="text" id="editRopaArt" name="art" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Cantidad (Stock)</label>
                        <input type="number" id="editRopaCantidad" name="cantidad" min="0" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Precio Unitario ($) <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" id="editRopaPrecio" name="precio" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 font-mono focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarModalEditarRopa()" class="px-4 py-2 text-slate-600 text-xs font-bold hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-black text-amber-300 text-xs font-black rounded-xl shadow transition-all cursor-pointer">
                        💾 Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL DUPLICAR PRENDA DE ROPA -->
    <div id="modalDuplicarRopa" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-indigo-500 text-white rounded-xl flex items-center justify-center font-bold">
                        📋
                    </div>
                    <div>
                        <h3 class="serif-title text-base font-extrabold text-amber-300">Duplicar Prenda</h3>
                        <p class="text-[11px] text-slate-400">Crea una copia de este artículo modificando los campos necesarios.</p>
                    </div>
                </div>
                <button onclick="cerrarModalDuplicarRopa()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.ropa.guardar') }}" method="POST" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
                @csrf
                <input type="hidden" name="categoria" value="{{ $categoriaActiva }}">
                <input type="hidden" id="dupRopaImagenPath" name="imagen_path" value="">

                <!-- Header de Vista Previa de la Prenda Original -->
                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 flex items-center space-x-3">
                    <img id="dupRopaImagenPreview" src="{{ asset('storage/ropa/default.png') }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 bg-white" alt="Vista Previa">
                    <div class="text-xs space-y-0.5">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Copiando datos desde:</span>
                        <span id="dupLblOriginalClave" class="font-mono font-black text-indigo-950 block"></span>
                        <span class="inline-flex items-center text-[10px] font-extrabold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            📷 Foto: No se guardará la foto original
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Marca <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="text" id="dupRopaMarca" name="marca" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estilo (Opcional)</label>
                        <input type="text" id="dupRopaEstilo" name="estilo" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Color <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="text" id="dupRopaColor" name="color" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Código de Barras (Opcional)</label>
                        <input type="text" id="dupRopaCodigo" name="codigo_barras" placeholder="Ej. 750123456789" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Art (Opcional)</label>
                        <input type="text" id="dupRopaArt" name="art" placeholder="Ej. ART-102" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 uppercase focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-900 mb-1">
                            Precio Unitario ($) <span class="text-rose-600">* (Obligatorio)</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" id="dupRopaPrecio" name="precio" required placeholder="0.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 font-mono focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Sección de Tallas Dinámicas (+ Talla) para Duplicado -->
                <div class="bg-indigo-50/70 p-4 rounded-2xl border border-indigo-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-extrabold text-slate-900 block">Tallas y Cantidades a Registrar <span class="text-rose-600">*</span></span>
                            <span class="text-[11px] text-slate-500">Puedes duplicar este artículo creando una o varias tallas a la vez.</span>
                        </div>
                        <button type="button" onclick="agregarFilaTallaDuplicarRopa()" class="px-3 py-1.5 bg-indigo-200 hover:bg-indigo-300 text-indigo-950 font-black text-xs rounded-lg transition-all cursor-pointer shrink-0">
                            + Agregar Otra Talla
                        </button>
                    </div>

                    <div id="contenedorFilasTallasDuplicarRopa" class="space-y-2">
                        <div class="flex items-center space-x-3 fila-talla-duplicar-ropa">
                            <div class="flex-1">
                                <input type="text" id="dupRopaTalla" name="tallas[0][talla]" required placeholder="Talla (Ej. CH, M, G, XL, 30, 32)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div class="w-32">
                                <input type="number" id="dupRopaCantidad" name="tallas[0][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                            </div>
                            <button type="button" onclick="eliminarFilaTallaDuplicarRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg hidden text-xs font-bold">
                                &times;
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarModalDuplicarRopa()" class="px-4 py-2 text-slate-600 text-xs font-bold hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow transition-all cursor-pointer">
                        📋 Registrar Duplicado en {{ $categoriaActiva }}
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL 5: NUEVA CATEGORÍA -->
    <div id="modalNuevaCategoriaRopa" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-amber-400 text-amber-950 rounded-xl flex items-center justify-center font-bold">
                        📂
                    </div>
                    <div>
                        <h3 class="serif-title text-base font-extrabold text-amber-300">Nueva Categoría de Ropa</h3>
                        <p class="text-[11px] text-slate-400">Crea un nuevo grupo para organizar el inventario.</p>
                    </div>
                </div>
                <button onclick="cerrarModalNuevaCategoria()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.ropa.categorias.guardar') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-extrabold text-slate-900 mb-1">Nombre de la Categoría <span class="text-rose-600">*</span></label>
                    <input type="text" name="nombre" required placeholder="Ej. PLAYERAS, PANTALONES, CHAMARRAS" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 uppercase focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarModalNuevaCategoria()" class="px-4 py-2 text-slate-600 text-xs font-bold hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-black text-amber-300 text-xs font-black rounded-xl shadow transition-all cursor-pointer">
                        💾 Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL 6: CONFIGURACIÓN CLAVE API GEMINI -->
    <div id="modalApiKeyRopa" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-amber-400 text-amber-950 rounded-xl flex items-center justify-center font-bold">
                        🔑
                    </div>
                    <div>
                        <h3 class="serif-title text-base font-extrabold text-amber-300">Clave API Gemini Vision</h3>
                        <p class="text-[11px] text-slate-400">Permite reconocer automáticamente etiquetas de ropa desde fotos.</p>
                    </div>
                </div>
                <button onclick="cerrarModalApiKey()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form id="formApiKeyRopa" onsubmit="guardarApiKeyRopa(event)" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-extrabold text-slate-900 mb-1">GEMINI_API_KEY</label>
                    <input type="password" id="inputGeminiApiKeyRopa" required placeholder="AIzaSy..." class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-800">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" onclick="cerrarModalApiKey()" class="px-4 py-2 text-slate-600 text-xs font-bold hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black rounded-xl shadow transition-all cursor-pointer">
                        💾 Guardar Clave API
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- JAVASCRIPT DE LÓGICA DE CÁMARA, ESCÁNER E INTERACCIÓN -->
    <script>
        let streamRopa = null;

        function abrirModalEscaner() {
            document.getElementById('modalEscanerRopa').classList.remove('hidden');
        }

        function cerrarModalEscaner() {
            detenerCamaraRopa();
            document.getElementById('modalEscanerRopa').classList.add('hidden');
        }

        function abrirCapturaManual() {
            document.getElementById('modalCapturaManualRopa').classList.remove('hidden');
        }

        function cerrarCapturaManual() {
            document.getElementById('modalCapturaManualRopa').classList.add('hidden');
        }

        function abrirModalNuevaCategoria() {
            document.getElementById('modalNuevaCategoriaRopa').classList.remove('hidden');
        }

        function cerrarModalNuevaCategoria() {
            document.getElementById('modalNuevaCategoriaRopa').classList.add('hidden');
        }

        function abrirModalApiKey() {
            document.getElementById('modalApiKeyRopa').classList.remove('hidden');
        }

        function cerrarModalApiKey() {
            document.getElementById('modalApiKeyRopa').classList.add('hidden');
        }

        // Cámara WebCam
        async function iniciarCamaraRopa() {
            const video = document.getElementById('videoElementRopa');
            const placeholder = document.getElementById('placeholderCamaraRopa');
            const imgPreview = document.getElementById('imagePreviewRopa');
            const btnCapturar = document.getElementById('btnCapturarRopa');

            try {
                streamRopa = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
                });
                video.srcObject = streamRopa;
                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
                imgPreview.classList.add('hidden');
                btnCapturar.classList.remove('hidden');
            } catch (err) {
                alert('No se pudo acceder a la cámara. Por favor asegúrate de otorgar los permisos en el navegador o sube un archivo.');
            }
        }

        function detenerCamaraRopa() {
            if (streamRopa) {
                streamRopa.getTracks().forEach(track => track.stop());
                streamRopa = null;
            }
            const video = document.getElementById('videoElementRopa');
            video.classList.add('hidden');
            document.getElementById('btnCapturarRopa').classList.add('hidden');
        }

        function capturarFotoRopa() {
            const video = document.getElementById('videoElementRopa');
            const canvas = document.getElementById('canvasElementRopa');
            const imgPreview = document.getElementById('imagePreviewRopa');

            canvas.width = video.videoWidth || 1280;
            canvas.height = video.videoHeight || 720;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const base64Image = canvas.toDataURL('image/jpeg', 0.85);
            imgPreview.src = base64Image;
            imgPreview.classList.remove('hidden');
            video.classList.add('hidden');

            detenerCamaraRopa();
            enviarImagenAnalisisRopa(null, base64Image);
        }

        function cargarArchivoRopa(event) {
            const file = event.target.files[0];
            if (!file) return;

            detenerCamaraRopa();
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgPreview = document.getElementById('imagePreviewRopa');
                document.getElementById('placeholderCamaraRopa').classList.add('hidden');
                imgPreview.src = e.target.result;
                imgPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);

            enviarImagenAnalisisRopa(file, null);
        }

        // Petición AJAX al backend para analizar con IA
        function enviarImagenAnalisisRopa(file, base64) {
            const loading = document.getElementById('loadingIaRopa');
            loading.classList.remove('hidden');

            const formData = new FormData();
            if (file) {
                formData.append('imagen_archivo', file);
            } else if (base64) {
                formData.append('imagen_base64', base64);
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('admin.ropa.analizar') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');
                if (data.success) {
                    limpiarFilasTallasEscanerRopa();
                    document.getElementById('escanerMarca').value = data.marca || '';
                    if (document.getElementById('escanerTalla')) {
                        document.getElementById('escanerTalla').value = data.talla || '';
                    }
                    document.getElementById('escanerEstilo').value = data.estilo || '';
                    document.getElementById('escanerColor').value = data.color || '';
                    document.getElementById('escanerCodigoBarras').value = data.codigo_barras || '';
                    document.getElementById('escanerArt').value = data.art || '';
                    document.getElementById('escanerImagenPathRopa').value = data.imagen_path || '';
                } else {
                    alert(data.error || 'Ocurrió un error al analizar la imagen.');
                }
            })
            .catch(err => {
                loading.classList.add('hidden');
                console.error(err);
                alert('Error al comunicarse con el servidor de análisis IA.');
            });
        }

        // Agregar / Eliminar filas de Tallas (+ Talla) en Escáner de Foto
        let contadorFilasTallasEscanerRopa = 1;

        function agregarFilaTallaEscanerRopa() {
            const contenedor = document.getElementById('contenedorFilasTallasEscanerRopa');
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-3 fila-talla-escaner-ropa animate-fade-in';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="tallas[${contadorFilasTallasEscanerRopa}][talla]" required placeholder="Talla (Ej. CH, M, G, XL)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800 focus:ring-2 focus:ring-amber-500">
                </div>
                <div class="w-32">
                    <input type="number" name="tallas[${contadorFilasTallasEscanerRopa}][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                </div>
                <button type="button" onclick="eliminarFilaTallaEscanerRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg text-xs font-bold">
                    &times;
                </button>
            `;
            contenedor.appendChild(div);
            contadorFilasTallasEscanerRopa++;
            actualizarBotonesEliminarTallaEscanerRopa();
        }

        function eliminarFilaTallaEscanerRopa(btn) {
            btn.closest('.fila-talla-escaner-ropa').remove();
            actualizarBotonesEliminarTallaEscanerRopa();
        }

        function actualizarBotonesEliminarTallaEscanerRopa() {
            const filas = document.querySelectorAll('#contenedorFilasTallasEscanerRopa .fila-talla-escaner-ropa');
            filas.forEach((f) => {
                const btn = f.querySelector('button');
                if (filas.length === 1) {
                    btn.classList.add('hidden');
                } else {
                    btn.classList.remove('hidden');
                }
            });
        }

        function limpiarFilasTallasEscanerRopa() {
            const contenedor = document.getElementById('contenedorFilasTallasEscanerRopa');
            if (!contenedor) return;
            contenedor.innerHTML = `
                <div class="flex items-center space-x-3 fila-talla-escaner-ropa">
                    <div class="flex-1">
                        <input type="text" id="escanerTalla" name="tallas[0][talla]" required placeholder="Talla (Ej. CH, M, G, XL, 30, 32)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800 focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div class="w-32">
                        <input type="number" name="tallas[0][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                    </div>
                    <button type="button" onclick="eliminarFilaTallaEscanerRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg hidden text-xs font-bold">
                        &times;
                    </button>
                </div>
            `;
            contadorFilasTallasEscanerRopa = 1;
        }

        // Agregar / Eliminar filas de Tallas (+ Talla) en Captura Manual
        let contadorFilasTallasRopa = 1;

        function agregarFilaTallaManualRopa() {
            const contenedor = document.getElementById('contenedorFilasTallasRopa');
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-3 fila-talla-ropa animate-fade-in';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="tallas[${contadorFilasTallasRopa}][talla]" required placeholder="Talla (Ej. CH, M, G, XL)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800">
                </div>
                <div class="w-32">
                    <input type="number" name="tallas[${contadorFilasTallasRopa}][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                </div>
                <button type="button" onclick="eliminarFilaTallaManualRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg text-xs font-bold">
                    &times;
                </button>
            `;
            contenedor.appendChild(div);
            contadorFilasTallasRopa++;
            actualizarBotonesEliminarTallaRopa();
        }

        function eliminarFilaTallaManualRopa(btn) {
            btn.closest('.fila-talla-ropa').remove();
            actualizarBotonesEliminarTallaRopa();
        }

        function actualizarBotonesEliminarTallaRopa() {
            const filas = document.querySelectorAll('#contenedorFilasTallasRopa .fila-talla-ropa');
            filas.forEach((f, idx) => {
                const btn = f.querySelector('button');
                if (filas.length === 1) {
                    btn.classList.add('hidden');
                } else {
                    btn.classList.remove('hidden');
                }
            });
        }

        // Abrir Modal + Talla para un modelo específico
        function abrirModalAgregarTallaRopa(ropaObj) {
            document.getElementById('addTallaMarca').value = ropaObj.marca || '';
            document.getElementById('addTallaEstilo').value = ropaObj.estilo || '';
            document.getElementById('addTallaColor').value = ropaObj.color || '';
            document.getElementById('addTallaCodigo').value = ropaObj.codigo_barras || '';
            document.getElementById('addTallaArt').value = ropaObj.art || '';
            document.getElementById('addTallaPrecio').value = ropaObj.precio || '0.00';

            document.getElementById('lblAddMarca').innerText = ropaObj.marca || '';
            document.getElementById('lblAddEstiloColor').innerText = `${ropaObj.estilo || ''} ${ropaObj.color || ''}`.trim() || 'General';
            document.getElementById('lblAddPrecio').innerText = parseFloat(ropaObj.precio || 0).toFixed(2);

            document.getElementById('modalAgregarTallaRopa').classList.remove('hidden');
        }

        function cerrarModalAgregarTallaRopa() {
            document.getElementById('modalAgregarTallaRopa').classList.add('hidden');
        }

        // Editar Prenda
        function abrirModalEditarRopa(ropaObj) {
            document.getElementById('editRopaMarca').value = ropaObj.marca || '';
            document.getElementById('editRopaTalla').value = ropaObj.talla || '';
            document.getElementById('editRopaEstilo').value = ropaObj.estilo || '';
            document.getElementById('editRopaColor').value = ropaObj.color || '';
            document.getElementById('editRopaCodigo').value = ropaObj.codigo_barras || '';
            document.getElementById('editRopaArt').value = ropaObj.art || '';
            document.getElementById('editRopaCantidad').value = ropaObj.cantidad || 0;
            document.getElementById('editRopaPrecio').value = ropaObj.precio || '0.00';

            const form = document.getElementById('formEditarRopa');
            form.action = `/admin/ropa/actualizar/${ropaObj.id}`;

            document.getElementById('modalEditarRopa').classList.remove('hidden');
        }

        function cerrarModalEditarRopa() {
            document.getElementById('modalEditarRopa').classList.add('hidden');
        }

        // Duplicar Prenda (Copiar y modificar campos + Múltiples Tallas)
        let contadorFilasTallasDuplicarRopa = 1;

        function agregarFilaTallaDuplicarRopa() {
            const contenedor = document.getElementById('contenedorFilasTallasDuplicarRopa');
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-3 fila-talla-duplicar-ropa animate-fade-in';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="tallas[${contadorFilasTallasDuplicarRopa}][talla]" required placeholder="Talla (Ej. CH, M, G, XL)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="w-32">
                    <input type="number" name="tallas[${contadorFilasTallasDuplicarRopa}][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                </div>
                <button type="button" onclick="eliminarFilaTallaDuplicarRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg text-xs font-bold">
                    &times;
                </button>
            `;
            contenedor.appendChild(div);
            contadorFilasTallasDuplicarRopa++;
            actualizarBotonesEliminarTallaDuplicarRopa();
        }

        function eliminarFilaTallaDuplicarRopa(btn) {
            btn.closest('.fila-talla-duplicar-ropa').remove();
            actualizarBotonesEliminarTallaDuplicarRopa();
        }

        function actualizarBotonesEliminarTallaDuplicarRopa() {
            const filas = document.querySelectorAll('#contenedorFilasTallasDuplicarRopa .fila-talla-duplicar-ropa');
            filas.forEach((f) => {
                const btn = f.querySelector('button');
                if (filas.length === 1) {
                    btn.classList.add('hidden');
                } else {
                    btn.classList.remove('hidden');
                }
            });
        }

        function limpiarFilasTallasDuplicarRopa() {
            const contenedor = document.getElementById('contenedorFilasTallasDuplicarRopa');
            if (!contenedor) return;
            contenedor.innerHTML = `
                <div class="flex items-center space-x-3 fila-talla-duplicar-ropa">
                    <div class="flex-1">
                        <input type="text" id="dupRopaTalla" name="tallas[0][talla]" required placeholder="Talla (Ej. CH, M, G, XL, 30, 32)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold uppercase text-slate-800 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="w-32">
                        <input type="number" id="dupRopaCantidad" name="tallas[0][cantidad]" min="1" value="1" required placeholder="Cant." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                    </div>
                    <button type="button" onclick="eliminarFilaTallaDuplicarRopa(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg hidden text-xs font-bold">
                        &times;
                    </button>
                </div>
            `;
            contadorFilasTallasDuplicarRopa = 1;
        }

        function abrirModalDuplicarRopa(ropaObj) {
            limpiarFilasTallasDuplicarRopa();

            document.getElementById('dupRopaMarca').value = ropaObj.marca || '';
            document.getElementById('dupRopaTalla').value = ropaObj.talla || '';
            document.getElementById('dupRopaCantidad').value = ropaObj.cantidad || 1;
            document.getElementById('dupRopaEstilo').value = ropaObj.estilo || '';
            document.getElementById('dupRopaColor').value = ropaObj.color || '';
            document.getElementById('dupRopaCodigo').value = ropaObj.codigo_barras || '';
            document.getElementById('dupRopaArt').value = ropaObj.art || '';
            document.getElementById('dupRopaPrecio').value = ropaObj.precio || '0.00';
            
            // Al duplicar NO se copia la foto del registro original (se deja limpia/default)
            document.getElementById('dupRopaImagenPath').value = '';

            const claveLbl = ropaObj.clave_alterna || 'S/C';
            const descLbl = `${ropaObj.marca || ''} ${ropaObj.talla || ''}`.trim();
            document.getElementById('dupLblOriginalClave').innerText = `${claveLbl} (${descLbl})`;
            document.getElementById('dupRopaImagenPreview').src = "{{ asset('storage/ropa/default.png') }}";

            document.getElementById('modalDuplicarRopa').classList.remove('hidden');
        }

        function cerrarModalDuplicarRopa() {
            document.getElementById('modalDuplicarRopa').classList.add('hidden');
        }

        // Guardar API Key de Gemini
        function guardarApiKeyRopa(e) {
            e.preventDefault();
            const key = document.getElementById('inputGeminiApiKeyRopa').value.trim();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('admin.ropa.apikey') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ gemini_api_key: key })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.mensaje);
                    cerrarModalApiKey();
                } else {
                    alert(data.error || 'Error al guardar la clave API.');
                }
            });
        }

        // Acciones Masivas (Seleccionar / Mover Ropa entre Categorías)
        function toggleSelectAllRopa(master) {
            const checkboxes = document.querySelectorAll('.ropa-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
            actualizarBarraAccionMasivaRopa();
        }

        function actualizarBarraAccionMasivaRopa() {
            const checkboxes = document.querySelectorAll('.ropa-checkbox:checked');
            const barra = document.getElementById('barraAccionesMasivas');
            const count = document.getElementById('cantSeleccionadosCount');

            if (checkboxes.length > 0) {
                count.innerText = checkboxes.length;
                barra.classList.remove('hidden');
            } else {
                barra.classList.add('hidden');
                document.getElementById('selectAllRopa').checked = false;
            }
        }

        function deseleccionarTodosRopa() {
            const checkboxes = document.querySelectorAll('.ropa-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('selectAllRopa').checked = false;
            actualizarBarraAccionMasivaRopa();
        }

        function ejecutarMoverRopaMasivo() {
            const checkboxes = document.querySelectorAll('.ropa-checkbox:checked');
            const destino = document.getElementById('selectCategoriaDestinoMasivo').value;

            if (checkboxes.length === 0) {
                alert('Por favor selecciona al menos una prenda para mover.');
                return;
            }

            if (!destino) {
                alert('Por favor selecciona una categoría de destino.');
                return;
            }

            const ids = Array.from(checkboxes).map(cb => cb.value);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!confirm(`¿Seguro que deseas mover ${ids.length} prenda(s) a la categoría '${destino}'?`)) {
                return;
            }

            fetch("{{ route('admin.ropa.mover_categoria') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    ropa_ids: ids,
                    categoria_destino: destino
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.mensaje);
                    window.location.href = "{{ route('admin.ropa') }}?categoria=" + encodeURIComponent(data.categoria_destino);
                } else {
                    alert(data.error || 'Error al mover la ropa.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Ocurrió un error al procesar la solicitud.');
            });
        }
    </script>
@endsection
