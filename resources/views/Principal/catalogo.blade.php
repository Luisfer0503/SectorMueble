@extends('layouts.app')

@section('titulo', 'Catálogo de Muebles de Diseño | Sector Mueble')

@section('contenido')
    <!-- Catalog Header -->
    <div class="bg-zinc-100 border-b border-zinc-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950">Catálogo de Muebles</h1>
                <p class="mt-1 text-zinc-500 text-sm">Explora nuestra colección cuidadosamente seleccionada para cada rincón de tu casa.</p>
            </div>
            <!-- Contador de resultados en header -->
            <div id="resultado-contador" class="text-sm text-zinc-500 font-medium shrink-0">
                <span id="num-resultados" class="font-bold text-zinc-900">{{ $productos->total() }}</span> muebles encontrados
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

            <!-- ====== SIDEBAR FILTROS ====== -->
            <aside class="lg:col-span-1 lg:sticky lg:top-24">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-lg overflow-hidden">

                    <!-- Cabecera -->
                    <div class="bg-gradient-to-r from-zinc-900 to-zinc-800 px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            <span class="text-white text-xs font-bold uppercase tracking-widest">Filtrar</span>
                        </div>
                        <!-- Indicador de carga en cabecera -->
                        <div id="filtro-spinner" class="hidden">
                            <svg class="animate-spin h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>
                        <!-- Botón limpiar dinámico -->
                        <button type="button" id="btn-limpiar" onclick="limpiarFiltros()"
                            class="hidden text-amber-400 hover:text-amber-300 text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center space-x-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Limpiar</span>
                        </button>
                    </div>

                    <!-- Chips de filtros activos (dinámicos) -->
                    <div id="chips-activos" class="hidden px-4 py-3 bg-amber-50 border-b border-amber-100 flex flex-wrap gap-1.5">
                        <!-- se rellena con JS -->
                    </div>

                    <form id="form-filtros" action="{{ route('catalogo') }}" method="GET" class="divide-y divide-zinc-100" onsubmit="return false;">

                        <!-- Buscador -->
                        <div class="px-5 py-4">
                            <label for="buscar" class="flex items-center space-x-1.5 text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-2.5">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Buscar</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="buscar"
                                    id="buscar"
                                    value="{{ request('buscar') }}"
                                    placeholder="Mesa, sofá, silla..."
                                    autocomplete="off"
                                    class="filtro-input w-full bg-zinc-50 border border-zinc-200 rounded-xl text-sm pl-9 pr-8 py-2.5 text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-700/25 focus:border-amber-700 transition-all"
                                >
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-zinc-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <!-- X para limpiar solo el buscador -->
                                <button type="button" id="clear-buscar" onclick="clearInput('buscar')"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-300 hover:text-zinc-600 hidden transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Categoría -->
                        <div class="px-5 py-4">
                            <span class="flex items-center space-x-1.5 text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-3">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Categoría</span>
                            </span>
                            <div class="space-y-1">
                                <label class="cat-label flex items-center space-x-3 cursor-pointer rounded-lg px-2 py-1.5 hover:bg-zinc-50 transition-colors group">
                                    <input type="radio" name="categoria" value="todas" class="filtro-input h-3.5 w-3.5 accent-amber-800 cursor-pointer"
                                        {{ request('categoria', 'todas') === 'todas' ? 'checked' : '' }}>
                                    <span class="text-sm text-zinc-700 group-hover:text-amber-800 font-medium transition-colors">Todas</span>
                                    <span class="ml-auto text-[10px] text-zinc-400 font-mono">{{ $productos->total() }}</span>
                                </label>
                                @foreach($categorias as $cat)
                                    <label class="cat-label flex items-center space-x-3 cursor-pointer rounded-lg px-2 py-1.5 hover:bg-zinc-50 transition-colors group">
                                        <input type="radio" name="categoria" value="{{ $cat }}" class="filtro-input h-3.5 w-3.5 accent-amber-800 cursor-pointer"
                                            {{ request('categoria') === $cat ? 'checked' : '' }}>
                                        <span class="text-sm text-zinc-600 group-hover:text-amber-800 transition-colors">{{ $cat }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rango de Precios -->
                        <div class="px-5 py-4">
                            <span class="flex items-center space-x-1.5 text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-3">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Precio ($ MXN)</span>
                            </span>
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400 text-xs font-bold">$</span>
                                    <input type="number" name="precio_min" id="precio_min" value="{{ request('precio_min') }}" placeholder="Mín" min="0"
                                        class="filtro-input w-full bg-zinc-50 border border-zinc-200 rounded-xl text-sm pl-6 pr-2 py-2.5 text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-700/25 focus:border-amber-700 transition-all">
                                </div>
                                <svg class="h-4 w-4 text-zinc-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                <div class="relative flex-1">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400 text-xs font-bold">$</span>
                                    <input type="number" name="precio_max" id="precio_max" value="{{ request('precio_max') }}" placeholder="Máx" min="0"
                                        class="filtro-input w-full bg-zinc-50 border border-zinc-200 rounded-xl text-sm pl-6 pr-2 py-2.5 text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-700/25 focus:border-amber-700 transition-all">
                                </div>
                            </div>
                            <!-- Chips de precio rápido -->
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                <button type="button" data-min="" data-max="5000" class="precio-chip text-[10px] font-semibold px-3 py-1.5 rounded-full border border-zinc-200 text-zinc-500 hover:border-amber-600 hover:text-amber-800 hover:bg-amber-50 transition-all">Hasta $5k</button>
                                <button type="button" data-min="5000" data-max="15000" class="precio-chip text-[10px] font-semibold px-3 py-1.5 rounded-full border border-zinc-200 text-zinc-500 hover:border-amber-600 hover:text-amber-800 hover:bg-amber-50 transition-all">$5k – $15k</button>
                                <button type="button" data-min="15000" data-max="" class="precio-chip text-[10px] font-semibold px-3 py-1.5 rounded-full border border-zinc-200 text-zinc-500 hover:border-amber-600 hover:text-amber-800 hover:bg-amber-50 transition-all">+$15k</button>
                            </div>
                        </div>

                        <!-- Ordenar -->
                        <div class="px-5 py-4">
                            <span class="flex items-center space-x-1.5 text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-3">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                                </svg>
                                <span>Ordenar por</span>
                            </span>
                            <div class="space-y-1">
                                @php
                                    $ordenActual = request('ordenar', 'novedad');
                                    $opcionesOrden = [
                                        'novedad'           => 'Novedades primero',
                                        'precio_asc'        => 'Precio: Bajo → Alto',
                                        'precio_desc'       => 'Precio: Alto → Bajo',
                                        'calificacion_desc' => 'Mejor valorados ★',
                                    ];
                                @endphp
                                @foreach($opcionesOrden as $val => $label)
                                    <label class="flex items-center space-x-3 cursor-pointer rounded-lg px-2 py-1.5 transition-all group
                                        {{ $ordenActual === $val ? 'bg-amber-50 border border-amber-200' : 'hover:bg-zinc-50 border border-transparent' }}">
                                        <input type="radio" name="ordenar" value="{{ $val }}" class="filtro-input h-3.5 w-3.5 accent-amber-800 cursor-pointer flex-shrink-0"
                                            {{ $ordenActual === $val ? 'checked' : '' }}>
                                        <span class="text-xs leading-tight transition-colors
                                            {{ $ordenActual === $val ? 'font-semibold text-amber-900' : 'text-zinc-600 group-hover:text-zinc-900' }}">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sólo en oferta (bonus) -->
                        <div class="px-5 py-4">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="oferta" id="oferta" value="1" class="filtro-input sr-only peer"
                                        {{ request('oferta') ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-zinc-200 peer-checked:bg-amber-700 rounded-full transition-colors duration-300"></div>
                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300 peer-checked:translate-x-4"></div>
                                </div>
                                <span class="text-sm text-zinc-600 group-hover:text-zinc-900 transition-colors">Solo en <strong class="text-rose-600">Oferta</strong></span>
                            </label>
                        </div>

                    </form>
                </div>
            </aside>

            <!-- ====== GRID DE PRODUCTOS ====== -->
            <div class="lg:col-span-3">
                <!-- Barra superior de resultados -->
                <div class="flex items-center justify-between mb-6">
                    <p id="texto-resultados" class="text-sm text-zinc-500">
                        Mostrando <span id="span-total" class="font-semibold text-zinc-800">{{ $productos->total() }}</span> resultado(s)
                    </p>
                    <div id="loading-badge" class="hidden items-center space-x-2 text-xs text-amber-800 font-semibold bg-amber-50 border border-amber-200 rounded-full px-3 py-1">
                        <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span>Actualizando...</span>
                    </div>
                </div>

                <!-- Contenedor de resultados -->
                <div id="productos-wrapper" class="transition-opacity duration-200">
                    @include('Principal.partials.productos-grid', ['productos' => $productos])
                </div>
            </div>

        </div>
    </div>

    {{-- ===== JAVASCRIPT DE FILTRADO EN TIEMPO REAL ===== --}}
    <script>
    (function () {
        const form       = document.getElementById('form-filtros');
        const wrapper    = document.getElementById('productos-wrapper');
        const spinner    = document.getElementById('filtro-spinner');
        const badge      = document.getElementById('loading-badge');
        const spanTotal  = document.getElementById('span-total');
        const numRes     = document.getElementById('num-resultados');
        const chipsBox   = document.getElementById('chips-activos');
        const btnLimpiar = document.getElementById('btn-limpiar');
        const clearBuscar= document.getElementById('clear-buscar');
        const inputBuscar= document.getElementById('buscar');
        const baseUrl    = '{{ route("catalogo") }}';
        let debounceTimer;

        // ─── Mostrar/ocultar X en buscador ───────────────────────────────────
        inputBuscar.addEventListener('input', () => {
            clearBuscar.classList.toggle('hidden', inputBuscar.value === '');
        });
        if (inputBuscar.value) clearBuscar.classList.remove('hidden');

        // ─── Limpiar campo individual ─────────────────────────────────────────
        window.clearInput = function(name) {
            const el = document.querySelector(`[name="${name}"]`);
            if (el) { el.value = ''; }
            clearBuscar.classList.add('hidden');
            triggerFetch();
        };

        // ─── Chips de precio rápido ───────────────────────────────────────────
        document.querySelectorAll('.precio-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('precio_min').value = btn.dataset.min;
                document.getElementById('precio_max').value = btn.dataset.max;
                // Visual activo
                document.querySelectorAll('.precio-chip').forEach(b => {
                    b.classList.remove('border-amber-600','text-amber-800','bg-amber-50');
                    b.classList.add('border-zinc-200','text-zinc-500');
                });
                btn.classList.add('border-amber-600','text-amber-800','bg-amber-50');
                btn.classList.remove('border-zinc-200','text-zinc-500');
                triggerFetch();
            });
        });

        // ─── Escuchar cambios en todos los inputs ─────────────────────────────
        document.querySelectorAll('.filtro-input').forEach(el => {
            const eventName = (el.type === 'text' || el.type === 'number') ? 'input' : 'change';
            el.addEventListener(eventName, () => {
                clearTimeout(debounceTimer);
                const delay = (el.type === 'text') ? 450 : 0;
                debounceTimer = setTimeout(triggerFetch, delay);
            });
        });

        // ─── Fetch principal ──────────────────────────────────────────────────
        function triggerFetch() {
            const params = new URLSearchParams(new FormData(form));
            // Limpiar valores vacíos
            for (const [k, v] of [...params.entries()]) {
                if (!v || v === 'todas') params.delete(k);
            }

            // Actualizar URL sin recargar
            const newUrl = params.toString() ? `${baseUrl}?${params}` : baseUrl;
            history.replaceState(null, '', newUrl);

            // UI: loading
            wrapper.style.opacity = '0.35';
            wrapper.style.pointerEvents = 'none';
            spinner.classList.remove('hidden');
            badge.classList.remove('hidden');
            badge.classList.add('flex');

            fetch(`${baseUrl}?${params}&_ajax=1`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser  = new DOMParser();
                const doc     = parser.parseFromString(html, 'text/html');
                const newGrid = doc.getElementById('productos-wrapper');
                const newTotal= doc.getElementById('span-total');

                if (newGrid) {
                    wrapper.innerHTML = newGrid.innerHTML;
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = '';
                }
                if (newTotal) {
                    const t = newTotal.textContent;
                    spanTotal.textContent = t;
                    numRes.textContent    = t;
                }

                spinner.classList.add('hidden');
                badge.classList.add('hidden');
                badge.classList.remove('flex');

                actualizarChips();
                actualizarOrdenLabels();
            })
            .catch(() => {
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = '';
                spinner.classList.add('hidden');
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            });
        }

        // ─── Chips dinámicos ──────────────────────────────────────────────────
        function actualizarChips() {
            const buscar   = document.querySelector('[name="buscar"]').value;
            const cat      = document.querySelector('[name="categoria"]:checked')?.value;
            const pMin     = document.getElementById('precio_min').value;
            const pMax     = document.getElementById('precio_max').value;
            const oferta   = document.getElementById('oferta').checked;
            let html = '';

            if (buscar) html += chip('🔍', buscar.substring(0,14), "clearInput('buscar')");
            if (cat && cat !== 'todas') html += chip('📦', cat, "resetCategoria()");
            if (pMin || pMax) html += chip('💰', `$${pMin||'0'} – $${pMax||'∞'}`, "clearPrecios()");
            if (oferta) html += chip('🏷️', 'Oferta', "clearOferta()");

            if (html) {
                chipsBox.innerHTML = html;
                chipsBox.classList.remove('hidden');
                btnLimpiar.classList.remove('hidden');
            } else {
                chipsBox.classList.add('hidden');
                btnLimpiar.classList.add('hidden');
            }
        }

        function chip(icon, text, fn) {
            return `<button type="button" onclick="${fn}" class="inline-flex items-center gap-1.5 bg-amber-800 hover:bg-amber-700 text-white text-[10px] font-semibold rounded-full pl-2 pr-1.5 py-1 transition-colors">
                        <span>${icon} ${text}</span>
                        <svg class="h-2.5 w-2.5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>`;
        }

        window.limpiarFiltros = function() {
            document.querySelector('[name="buscar"]').value = '';
            document.querySelector('[name="categoria"][value="todas"]').checked = true;
            document.getElementById('precio_min').value = '';
            document.getElementById('precio_max').value = '';
            document.getElementById('oferta').checked = false;
            document.querySelectorAll('.precio-chip').forEach(b => {
                b.classList.remove('border-amber-600','text-amber-800','bg-amber-50');
                b.classList.add('border-zinc-200','text-zinc-500');
            });
            clearBuscar.classList.add('hidden');
            triggerFetch();
        };
        window.resetCategoria = function() {
            document.querySelector('[name="categoria"][value="todas"]').checked = true;
            triggerFetch();
        };
        window.clearPrecios = function() {
            document.getElementById('precio_min').value = '';
            document.getElementById('precio_max').value = '';
            document.querySelectorAll('.precio-chip').forEach(b => {
                b.classList.remove('border-amber-600','text-amber-800','bg-amber-50');
                b.classList.add('border-zinc-200','text-zinc-500');
            });
            triggerFetch();
        };
        window.clearOferta = function() {
            document.getElementById('oferta').checked = false;
            triggerFetch();
        };

        // ─── Resaltar radio activo de ordenar ─────────────────────────────────
        function actualizarOrdenLabels() {
            document.querySelectorAll('[name="ordenar"]').forEach(r => {
                const lbl = r.closest('label');
                if (!lbl) return;
                const span = lbl.querySelector('span');
                if (r.checked) {
                    lbl.classList.add('bg-amber-50','border-amber-200');
                    lbl.classList.remove('hover:bg-zinc-50','border-transparent');
                    if (span) { span.classList.add('font-semibold','text-amber-900'); span.classList.remove('text-zinc-600'); }
                } else {
                    lbl.classList.remove('bg-amber-50','border-amber-200');
                    lbl.classList.add('hover:bg-zinc-50','border-transparent');
                    if (span) { span.classList.remove('font-semibold','text-amber-900'); span.classList.add('text-zinc-600'); }
                }
            });
        }

        // Inicializar chips al cargar si vienen filtros de URL
        actualizarChips();
        actualizarOrdenLabels();
    })();
    </script>
@endsection
