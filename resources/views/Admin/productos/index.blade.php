@extends('layouts.admin')

@section('contenido')
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-zinc-200 mb-8 gap-4">
        <div>
            <h1 class="serif-title text-3xl font-bold text-zinc-950">Muebles en el Catálogo</h1>
            <p class="text-zinc-500 text-sm mt-1">Gestiona el catálogo completo de productos visibles en la tienda.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button type="button" onclick="openFeedModal()" class="bg-indigo-700 hover:bg-indigo-800 text-white text-xs font-bold uppercase tracking-wider px-5 py-3 rounded shadow transition-colors flex items-center space-x-2 cursor-pointer">
                <svg class="w-4 h-4 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                </svg>
                <span>📡 Feed XML Marketing</span>
            </button>
            <a href="{{ route('admin.productos.excel') }}" class="bg-emerald-750 hover:bg-emerald-800 text-white text-xs font-bold uppercase tracking-wider px-5 py-3 rounded shadow transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>📊 Exportar Excel</span>
            </a>
            <a href="{{ route('admin.productos.crear') }}" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded shadow transition-colors">
                + Agregar Mueble
            </a>
        </div>
    </div>

    <!-- Buscador de Muebles en el Admin -->
    <div class="bg-white border border-zinc-200 rounded-2xl p-4 shadow-xs mb-6">
        <form action="{{ route('admin.productos') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="relative w-full sm:max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    name="buscar"
                    id="admin-search-input"
                    value="{{ request('buscar') }}"
                    placeholder="Buscar por nombre, categoría, SKU o proveedor..."
                    class="w-full pl-10 pr-10 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-medium text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-850/20 focus:border-amber-800 transition-all">
                @if(request('buscar'))
                    <a href="{{ route('admin.productos') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition-colors" title="Limpiar búsqueda">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button type="submit" class="w-full sm:w-auto bg-zinc-900 hover:bg-amber-850 text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Buscar Mueble</span>
                </button>
                @if(request('buscar'))
                    <a href="{{ route('admin.productos') }}" class="bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-bold px-4 py-2.5 rounded-xl transition-all">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>

        @if(request('buscar'))
            <div class="mt-3 pt-3 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                <span>Mostrando resultados para: <strong class="text-amber-900 font-bold">"{{ request('buscar') }}"</strong></span>
                <span class="font-bold text-zinc-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">{{ $productos->total() }} mueble(s) encontrado(s)</span>
            </div>
        @endif
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm overflow-hidden">
        @if($productos->isEmpty())
            <div class="text-center py-20 px-4 text-zinc-500">
                @if(request('buscar'))
                    <div class="max-w-md mx-auto">
                        <span class="text-4xl block mb-2">🔍</span>
                        <h3 class="text-base font-bold text-zinc-900">Sin coincidencias para "{{ request('buscar') }}"</h3>
                        <p class="text-xs text-zinc-500 mt-1">Verifica la ortografía o intenta buscar por otra categoría o proveedor.</p>
                        <a href="{{ route('admin.productos') }}" class="inline-block mt-4 bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                            Ver todo el catálogo
                        </a>
                    </div>
                @else
                    <span class="text-4xl block mb-2">🪵</span>
                    <p class="text-sm font-semibold">No hay productos en el catálogo actualmente.</p>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-zinc-50 text-xs font-bold text-zinc-450 border-b border-zinc-200 uppercase tracking-wider">
                            <th class="p-4 pl-6">Mueble</th>
                            <th class="p-4">Categoría</th>
                            <th class="p-4">Visibilidad</th>
                            <th class="p-4">Precio</th>
                            <th class="p-4">Inventario</th>
                            <th class="p-4">Calificación</th>
                            <th class="p-4 text-right pr-6">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($productos as $producto)
                            <tr class="hover:bg-zinc-50/50 transition-colors {{ !$producto->activo ? 'bg-rose-50/20' : '' }}">
                                <!-- Imagen & Nombre -->
                                <td class="p-4 pl-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded bg-zinc-100 overflow-hidden flex-shrink-0 border border-zinc-200">
                                            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <span class="font-semibold text-zinc-900 block">{{ $producto->nombre }}</span>
                                            <div class="flex flex-wrap items-center gap-1 mt-0.5">
                                                @if($producto->destacado)
                                                    <span class="inline-block text-[9px] font-bold text-amber-800 bg-amber-50 border border-amber-200 px-1.5 py-0.5 uppercase tracking-wider rounded">Destacado</span>
                                                @endif
                                                <span class="inline-block text-[9px] font-bold text-zinc-700 bg-zinc-100 border border-zinc-200 px-1.5 py-0.5 uppercase tracking-wider rounded">
                                                    🏢 {{ $producto->proveedor ?? 'Casa Tapier' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Categoría -->
                                <td class="p-4 text-zinc-500 font-medium">{{ $producto->categoria }}</td>
                                
                                <!-- Visibilidad / Botón Activo/Inactivo -->
                                <td class="p-4">
                                    <form action="{{ route('admin.productos.toggle_activo', $producto->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-2xs cursor-pointer {{ $producto->activo ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-300 hover:bg-rose-200' }}" title="Haz clic para {{ $producto->activo ? 'desactivar' : 'activar' }}">
                                            <span class="w-2 h-2 rounded-full {{ $producto->activo ? 'bg-emerald-600 animate-pulse' : 'bg-rose-600' }}"></span>
                                            <span>{{ $producto->activo ? 'Activo (Público)' : 'Inactivo (Oculto)' }}</span>
                                        </button>
                                    </form>
                                </td>

                                <!-- Precio -->
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        @if($producto->tieneDescuento())
                                            <span class="font-semibold text-zinc-400 line-through text-xs font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                            <span class="font-bold text-emerald-700 font-sans">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }}</span>
                                            <span class="text-[9px] font-bold text-white bg-rose-600 rounded px-1.5 py-0.5 w-fit mt-0.5">-{{ $producto->porcentaje_descuento }}%</span>
                                        @else
                                            <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Inventario / Stock -->
                                <td class="p-4">
                                    @if($producto->stock > 5)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 font-sans">
                                            {{ $producto->stock }} disp.
                                        </span>
                                    @elseif($producto->stock > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 font-sans">
                                            {{ $producto->stock }} crít.
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200 font-sans">
                                            Sin Stock
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Calificación -->
                                <td class="p-4">
                                    <span class="text-amber-500 font-bold flex items-center text-xs">
                                        <svg class="h-3.5 w-3.5 fill-current mr-0.5" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ number_format($producto->calificacion, 1) }}
                                    </span>
                                </td>
                                
                                <!-- Acciones -->
                                <td class="p-4 text-right pr-6 space-x-3">
                                    <a href="{{ route('admin.productos.descuento', $producto->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">Descuento</a>
                                    <a href="{{ route('admin.productos.editar', $producto->id) }}" class="text-xs font-bold text-amber-850 hover:underline">Editar</a>
                                    <a href="{{ route('admin.productos.eliminar', $producto->id) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar este mueble del catálogo?')" class="text-xs font-bold text-rose-600 hover:underline">Eliminar</a>
                                </td>
                            </tr>

                            <!-- Subartículos (Producto Detalles) -->
                            <tr class="bg-zinc-50/60 border-b border-zinc-200">
                                <td colspan="7" class="p-3 pl-8 sm:pl-12 pr-6">
                                    <div class="bg-white border border-zinc-200 rounded-xl p-3.5 shadow-2xs">
                                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-zinc-150">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs">📦</span>
                                                <span class="text-xs font-bold uppercase tracking-wider text-zinc-700">Subartículos (Detalles)</span>
                                                <span class="text-[10px] font-bold text-amber-900 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                                                    {{ $producto->detalles->count() }} subartículo(s)
                                                </span>
                                            </div>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-xs">
                                                <thead>
                                                    <tr class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider border-b border-zinc-100">
                                                        <th class="py-1.5 px-2">ID Producto</th>
                                                        <th class="py-1.5 px-2">SKU</th>
                                                        <th class="py-1.5 px-2">Imagen</th>
                                                        <th class="py-1.5 px-2">Nombre del Subartículo</th>
                                                        <th class="py-1.5 px-2">Precio</th>
                                                        <th class="py-1.5 px-2">Stock</th>
                                                        <th class="py-1.5 px-2">Visibilidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-zinc-50">
                                                    @forelse($producto->detalles as $det)
                                                        <tr class="hover:bg-zinc-50 transition-colors {{ !$det->activo ? 'bg-rose-50/30' : '' }}">
                                                            <td class="py-2 px-2 font-mono font-bold text-zinc-500">#{{ $det->producto_id }}</td>
                                                            <td class="py-2 px-2 font-mono text-zinc-600 font-semibold">{{ $det->sku ?? '-' }}</td>
                                                            <td class="py-2 px-2">
                                                                <div class="w-8 h-8 rounded border border-zinc-200 overflow-hidden bg-zinc-100 flex items-center justify-center">
                                                                    <img src="{{ $det->imagen_url }}" alt="{{ $det->nombre }}" class="w-full h-full object-cover">
                                                                </div>
                                                            </td>
                                                            <td class="py-2 px-2 font-semibold text-zinc-800">{{ $det->nombre }}</td>
                                                            <td class="py-2 px-2 font-bold text-emerald-700 font-sans">$ {{ number_format($det->precio ?? $producto->precio, 2, '.', ',') }}</td>
                                                            <td class="py-2 px-2">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-zinc-100 text-zinc-700 font-sans">
                                                                    {{ $det->stock }} disp.
                                                                </span>
                                                            </td>
                                                            <td class="py-2 px-2">
                                                                <form action="{{ route('admin.productos.subarticulo_toggle_activo', $det->id) }}" method="POST" class="inline-block">
                                                                    @csrf
                                                                    <button type="submit" class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold transition-all cursor-pointer {{ $det->activo ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-300 hover:bg-rose-200' }}" title="Haz clic para {{ $det->activo ? 'desactivar' : 'activar' }}">
                                                                        <span class="w-1.5 h-1.5 rounded-full {{ $det->activo ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                                                                        <span>{{ $det->activo ? 'Activo' : 'Inactivo' }}</span>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="py-2 px-2 text-zinc-400 italic text-[11px]">
                                                                Sin subartículos registrados.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-zinc-200">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

    {{-- Script para filtrado rápido en el cliente mientras se escribe --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('admin-search-input');
            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                const tbody = document.querySelector('table tbody');
                if (!tbody) return;

                const rows = Array.from(tbody.querySelectorAll(':scope > tr'));
                
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    if (row.classList.contains('bg-zinc-50/60')) {
                        continue;
                    }
                    
                    const nextRow = rows[i + 1] && rows[i + 1].classList.contains('bg-zinc-50/60') ? rows[i + 1] : null;
                    const text = (row.textContent + ' ' + (nextRow ? nextRow.textContent : '')).toLowerCase();
                    
                }
            });
        });

        function openFeedModal() {
            document.getElementById('feed-xml-modal').classList.remove('hidden');
        }

        function closeFeedModal() {
            document.getElementById('feed-xml-modal').classList.add('hidden');
        }

        function copyFeedUrl(inputContainerId, btnId) {
            const input = document.getElementById(inputContainerId);
            if (!input) return;
            navigator.clipboard.writeText(input.value).then(() => {
                const btn = document.getElementById(btnId);
                const originalText = btn.innerText;
                btn.innerText = '✓ ¡Copiado!';
                btn.classList.add('bg-emerald-600', 'text-white');
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.classList.remove('bg-emerald-600', 'text-white');
                }, 2000);
            }).catch(err => {
                alert('URL copiada: ' + input.value);
            });
        }
    </script>

    <!-- Modal: URL de Feeds XML para Marketing y Redes Sociales -->
    <div id="feed-xml-modal" class="fixed inset-0 bg-zinc-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-zinc-200 overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-gradient-to-r from-indigo-900 to-zinc-900 p-6 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center border border-indigo-400/30">
                        <svg class="w-5 h-5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-white">Feeds XML para Marketing</h3>
                        <p class="text-xs text-indigo-200">Conecta tu catálogo con Meta, Google Shopping, TikTok y Pinterest</p>
                    </div>
                </div>
                <button type="button" onclick="closeFeedModal()" class="text-indigo-200 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-white/10 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-5">
                <p class="text-xs text-zinc-600 leading-relaxed">
                    Utiliza cualquiera de estas URLs públicas en los administradores de catálogo (ej. <strong>Meta Commerce Manager</strong>, <strong>Google Merchant Center</strong>, <strong>TikTok Ads Manager</strong>) para sincronizar automáticamente todos los productos y variantes activos de la tienda.
                </p>

                <!-- URL Principal XML -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-1.5 flex items-center justify-between">
                        <span>📡 URL de Feed Principal (Universal RSS 2.0 XML)</span>
                        <span class="text-[10px] text-emerald-600 font-bold">Meta / Google / TikTok</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="feed-url-main" readonly value="{{ url('/feed/productos.xml') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-800 font-mono focus:outline-none select-all">
                        <button type="button" id="btn-copy-main" onclick="copyFeedUrl('feed-url-main', 'btn-copy-main')" class="bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shrink-0 cursor-pointer">
                            Copiar URL
                        </button>
                        <a href="{{ url('/feed/productos.xml') }}" target="_blank" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-2.5 rounded-xl transition-all shrink-0 flex items-center space-x-1" title="Abrir XML en nueva pestaña">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- URL Alias Facebook / Meta -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-1.5 flex items-center justify-between">
                        <span>🔵 Facebook & Instagram Catalog URL</span>
                        <span class="text-[10px] text-zinc-400">Meta Commerce</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="feed-url-fb" readonly value="{{ url('/feed/facebook-catalog.xml') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-800 font-mono focus:outline-none select-all">
                        <button type="button" id="btn-copy-fb" onclick="copyFeedUrl('feed-url-fb', 'btn-copy-fb')" class="bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shrink-0 cursor-pointer">
                            Copiar URL
                        </button>
                    </div>
                </div>

                <!-- URL Alias Google Shopping -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-1.5 flex items-center justify-between">
                        <span>🔴 Google Merchant Center URL</span>
                        <span class="text-[10px] text-zinc-400">Google Shopping</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="feed-url-google" readonly value="{{ url('/feed/google-shopping.xml') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-800 font-mono focus:outline-none select-all">
                        <button type="button" id="btn-copy-google" onclick="copyFeedUrl('feed-url-google', 'btn-copy-google')" class="bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shrink-0 cursor-pointer">
                            Copiar URL
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-zinc-50 px-6 py-4 border-t border-zinc-200 flex justify-end">
                <button type="button" onclick="closeFeedModal()" class="bg-zinc-200 hover:bg-zinc-300 text-zinc-800 font-bold text-xs px-5 py-2.5 rounded-xl transition-all cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endsection

