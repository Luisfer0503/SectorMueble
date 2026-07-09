@extends('layouts.admin')

@section('contenido')
    <!-- Header -->
    <div class="pb-6 border-b border-zinc-200 mb-8">
        <nav class="flex text-xs font-medium text-zinc-500 space-x-2 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-850">Inicio</a>
            <span>/</span>
            <a href="{{ route('admin.productos') }}" class="hover:text-amber-850">Muebles</a>
            <span>/</span>
            <span class="text-zinc-800">Descuento Directo</span>
        </nav>
        <h1 class="serif-title text-3xl font-bold text-zinc-950">Descuento Directo al Producto</h1>
        <p class="text-zinc-500 text-sm mt-1">Aplica un porcentaje de descuento que se verá en el catálogo y ficha de detalle.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-4xl">

        <!-- Formulario (izquierda - col 2) -->
        <div class="lg:col-span-2">

            <!-- Tarjeta del producto -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm mb-6 flex items-center space-x-4">
                <div class="w-20 h-20 rounded bg-zinc-100 overflow-hidden flex-shrink-0">
                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h2 class="font-bold text-zinc-950 text-base truncate">{{ $producto->nombre }}</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Categoría: {{ $producto->categoria }}</p>
                    <p class="text-sm font-semibold text-zinc-700 mt-2 font-sans">
                        Precio base: <span class="text-amber-800">$ {{ number_format($producto->precio, 2, '.', ',') }} MXN</span>
                    </p>
                </div>
            </div>

            <!-- Formulario de descuento -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                <form action="{{ route('admin.productos.aplicar_descuento', $producto->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Porcentaje de descuento -->
                    <div>
                        <label for="porcentaje_descuento" class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">
                            Porcentaje de Descuento (%)
                        </label>
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-grow">
                                <input
                                    type="number"
                                    name="porcentaje_descuento"
                                    id="porcentaje_descuento"
                                    min="1"
                                    max="99"
                                    value="{{ old('porcentaje_descuento', $producto->porcentaje_descuento ?? '') }}"
                                    placeholder="Ej: 20"
                                    class="w-full bg-zinc-50 border border-zinc-200 rounded text-2xl font-bold text-zinc-900 px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-amber-700 transition-all"
                                    oninput="calcularPreviaSE(this.value)"
                                >
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-2xl font-bold text-zinc-400">%</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-1.5">Ingresa un valor entre 1 y 99. El precio con descuento se calcula automáticamente.</p>
                        @error('porcentaje_descuento')
                            <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Preview del precio resultante -->
                    <div class="bg-amber-50 border border-amber-200 rounded p-4" id="preview-box">
                        <p class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-3">Vista Previa del Precio</p>
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <span class="block text-[10px] font-semibold text-zinc-500 uppercase tracking-wider mb-1">Precio Original</span>
                                <span class="block text-sm font-semibold text-zinc-500 line-through font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                            </div>
                            <div class="text-zinc-300 text-xl">→</div>
                            <div class="text-center">
                                <span class="block text-[10px] font-semibold text-emerald-700 uppercase tracking-wider mb-1">Precio Final</span>
                                <span id="precio-preview" class="block text-2xl font-bold text-emerald-700 font-sans">
                                    @if($producto->tieneDescuento())
                                        $ {{ number_format($producto->precio_descuento, 2, '.', ',') }}
                                    @else
                                        $ {{ number_format($producto->precio, 2, '.', ',') }}
                                    @endif
                                </span>
                            </div>
                            <div class="text-center ml-auto">
                                <span id="badge-preview" class="inline-block text-xs font-bold text-white bg-rose-600 rounded px-3 py-1 font-sans hidden">
                                    -<span id="badge-pct">0</span>%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex flex-col sm:flex-row items-center justify-between pt-4 border-t border-zinc-150 gap-3">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.productos') }}" class="border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider px-5 py-3 rounded transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded shadow transition-colors">
                                Aplicar Descuento
                            </button>
                        </div>

                        <!-- Quitar descuento (si hay uno activo) -->
                        @if($producto->tieneDescuento())
                            <button type="submit" name="quitar_descuento" value="1"
                                onclick="return confirm('¿Deseas quitar el descuento de este mueble?')"
                                class="border border-rose-200 hover:bg-rose-50 text-rose-700 text-xs font-bold uppercase tracking-wider px-5 py-3 rounded transition-colors">
                                Quitar Descuento Activo
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel lateral informativo (derecha - col 1) -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm sticky top-24">
                <h3 class="serif-title text-base font-bold text-zinc-950 mb-4">Estado del Descuento</h3>

                @if($producto->tieneDescuento())
                    <div class="bg-rose-50 border border-rose-200 rounded p-4 mb-4">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="bg-rose-600 text-white text-xs font-bold px-2 py-0.5 rounded">-{{ $producto->porcentaje_descuento }}%</span>
                            <span class="text-xs font-bold text-rose-800">Descuento Activo</span>
                        </div>
                        <p class="text-xs text-zinc-700 mt-2">
                            <strong>Precio original:</strong><br>
                            <span class="font-sans line-through text-zinc-500">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                        </p>
                        <p class="text-xs text-zinc-700 mt-1">
                            <strong>Precio con descuento:</strong><br>
                            <span class="font-sans text-emerald-700 font-bold text-base">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }}</span>
                        </p>
                        <p class="text-xs text-zinc-500 mt-2">El cliente ve el precio tachado y el precio final en el catálogo.</p>
                    </div>
                @else
                    <div class="bg-zinc-50 border border-zinc-200 rounded p-4 mb-4 text-xs text-zinc-500">
                        <p>Este mueble <strong>no tiene descuento directo activo</strong>.</p>
                        <p class="mt-1">Al aplicar uno, el precio tachado aparecerá en el catálogo y en la ficha del producto.</p>
                    </div>
                @endif

                <div class="text-[10px] text-zinc-400 space-y-2 mt-4 border-t border-zinc-100 pt-4">
                    <p><span class="font-bold text-zinc-600">Nota:</span> El descuento directo afecta solo a este artículo.</p>
                    <p>Para descuentos globales o por código, utiliza los <strong>Cupones</strong> en el menú lateral.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const precioBase = {{ $producto->precio }};

        function calcularPreviaSE(pct) {
            const n = parseInt(pct, 10);
            const badge = document.getElementById('badge-preview');
            const preview = document.getElementById('precio-preview');
            const badgePct = document.getElementById('badge-pct');

            if (!isNaN(n) && n > 0 && n < 100) {
                const descuentado = precioBase * (1 - n / 100);
                preview.textContent = '$ ' + descuentado.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                badge.classList.remove('hidden');
                badgePct.textContent = n;
            } else {
                preview.textContent = '$ ' + precioBase.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                badge.classList.add('hidden');
            }
        }

        // Inicializar si ya hay un porcentaje cargado
        const inputInicial = document.getElementById('porcentaje_descuento').value;
        if (inputInicial) calcularPreviaSE(inputInicial);
    </script>
@endsection
