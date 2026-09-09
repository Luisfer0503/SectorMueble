{{-- Partial: grid de productos del catálogo --}}
{{-- Se usa tanto en la carga inicial como en respuestas AJAX --}}

<div id="productos-wrapper" class="transition-opacity duration-200">

    @if($productos->isEmpty())
        {{-- Estado vacío --}}
        <div class="flex flex-col items-center justify-center py-24 bg-white border border-zinc-200 rounded-2xl text-center px-8">
            <div class="w-16 h-16 bg-zinc-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-zinc-900">Sin resultados</h3>
            <p class="mt-1 text-zinc-500 text-sm max-w-xs">No encontramos muebles con esos criterios. Prueba cambiando los filtros.</p>
        </div>

    @else

        {{-- Contador oculto para JS --}}
        <span id="span-total" class="hidden">{{ $productos->total() }}</span>

        {{-- Grid de productos (2 columnas en móviles, 3 en desktop) --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
            @foreach($productos as $producto)
                <article class="group relative bg-white flex flex-col border border-zinc-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">

                    {{-- Imagen --}}
                    <div class="relative w-full h-44 sm:h-64 bg-zinc-100 overflow-hidden">
                        {{-- Foto 1 (Principal) --}}
                        <img
                            id="img-prod-{{ $producto->id }}"
                            src="{{ $producto->imagen_url }}"
                            alt="{{ $producto->nombre }}"
                            loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >

                        {{-- Foto 2 (Secundaria en Hover) --}}
                        @if($producto->imagen_secundaria_url)
                            <img
                                id="sec-img-prod-{{ $producto->id }}"
                                src="{{ $producto->imagen_secundaria_url }}"
                                alt="{{ $producto->nombre }} (Secundaria)"
                                loading="lazy"
                                class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500"
                            >
                        @endif

                        {{-- Badges --}}
                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 flex flex-col space-y-1">
                            @if($producto->tieneDescuento())
                                <span class="inline-flex items-center gap-1 bg-rose-600 text-white text-[8px] sm:text-[9px] font-bold px-1.5 sm:px-2 py-0.5 uppercase rounded-full tracking-wider shadow">
                                    -{{ $producto->porcentaje_descuento }}%
                                </span>
                            @endif
                            @if($producto->destacado)
                                <span class="inline-flex items-center gap-1 bg-amber-800 text-white text-[8px] sm:text-[9px] font-bold px-1.5 sm:px-2 py-0.5 uppercase rounded-full tracking-wider shadow">
                                    ★ Destacado
                                </span>
                            @endif
                            @if($producto->stock > 0 && $producto->stock <= 5)
                                <span class="inline-flex items-center gap-1 bg-orange-500 text-white text-[8px] sm:text-[9px] font-bold px-1.5 sm:px-2 py-0.5 uppercase rounded-full tracking-wider shadow">
                                    Últimas {{ $producto->stock }}
                                </span>
                            @endif
                        </div>

                        {{-- Hover overlay con botón Ver Detalles --}}
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <a href="{{ route('productos.detalle', $producto->id) }}"
                               class="bg-white text-zinc-900 text-xs font-bold px-4 sm:px-5 py-2 sm:py-2.5 rounded-full shadow-lg hover:bg-amber-800 hover:text-white transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                Ver Detalles
                            </a>
                        </div>
                    </div>

                    {{-- Info del producto --}}
                    <div class="p-3.5 sm:p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-wider block truncate">{{ $producto->categoria }}</span>
                            <h3 class="text-xs sm:text-base font-bold text-zinc-950 mt-1 leading-snug line-clamp-2">
                                <a href="{{ route('productos.detalle', $producto->id) }}" class="hover:text-amber-800 transition-colors">
                                    {{ $producto->nombre }}
                                </a>
                            </h3>

                            {{-- Rating --}}
                            <div class="flex items-center mt-1.5 space-x-1">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-3 w-3 {{ $i <= round($producto->calificacion) ? 'text-amber-400 fill-current' : 'text-zinc-200 fill-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-zinc-500 font-bold">{{ number_format($producto->calificacion, 1) }}</span>
                            </div>

                            {{-- Combinaciones / Acabados disponibles --}}
                            @php
                                $detallesActivos = $producto->detalles ? $producto->detalles->where('activo', true) : collect();
                            @endphp
                            @if($detallesActivos->count() > 0)
                                <div class="mt-2.5 pt-2 border-t border-zinc-100/80">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-amber-900 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 inline-flex items-center gap-1">
                                            <span>🎨</span>
                                            <span>+{{ $detallesActivos->count() }} {{ $detallesActivos->count() === 1 ? 'combinación' : 'combinaciones' }}</span>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 overflow-x-auto py-1 scrollbar-none">
                                        @foreach($detallesActivos as $idx => $det)
                                            <button
                                                type="button"
                                                title="{{ $det->nombre }}"
                                                onclick="event.stopPropagation(); cambiarImagenCard(this, 'img-prod-{{ $producto->id }}', '{{ $det->imagen_url }}', '{{ $det->id }}', 'form-add-{{ $producto->id }}')"
                                                class="btn-var-thumb relative w-7 h-7 sm:w-8 sm:h-8 rounded-lg overflow-hidden border-2 transition-all duration-200 shrink-0 focus:outline-none {{ $idx === 0 ? 'border-[#88674B] ring-2 ring-[#88674B]/30 scale-105' : 'border-zinc-200 hover:border-[#88674B]' }}">
                                                <img src="{{ $det->imagen_url }}" alt="{{ $det->nombre }}" class="w-full h-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Precio y botón Añadir --}}
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between mt-3 sm:mt-4 pt-3 border-t border-zinc-100 gap-2.5 sm:gap-0">
                            <div class="flex flex-col">
                                @if($producto->tieneDescuento())
                                    <span class="text-xs text-zinc-400 line-through font-sans leading-tight">
                                        $ {{ number_format($producto->precio, 2, '.', ',') }}
                                    </span>
                                    <span class="text-sm sm:text-base font-extrabold text-emerald-800 font-sans leading-tight">
                                        $ {{ number_format($producto->precio_descuento, 2, '.', ',') }}
                                        <span class="text-xs font-bold text-rose-600">(-{{ $producto->porcentaje_descuento }}%)</span>
                                    </span>
                                @else
                                    <span class="text-sm sm:text-base font-extrabold text-zinc-950 font-sans">
                                        $ {{ number_format($producto->precio, 2, '.', ',') }}
                                    </span>
                                @endif
                                <span class="text-[10px] text-zinc-500 font-semibold">MXN</span>
                            </div>

                            @if($producto->stock > 0)
                                <form
                                    id="form-add-{{ $producto->id }}"
                                    action="{{ route('carrito.agregar', $producto->id) }}"
                                    method="POST"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-img="{{ $producto->imagen_url }}"
                                    class="w-full sm:w-auto"
                                    onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                                    @csrf
                                    <input type="hidden" name="subarticulo_id" value="{{ $detallesActivos->first()->id ?? '' }}">
                                    <button type="submit"
                                        class="w-full sm:w-auto flex items-center justify-center space-x-1.5 text-xs font-bold bg-zinc-950 hover:bg-amber-800 text-white rounded-xl px-3.5 py-2.5 transition-all duration-300 shadow active:scale-95 group/btn">
                                        <svg class="h-3.5 w-3.5 transition-transform group-hover/btn:rotate-90 duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>Añadir</span>
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider bg-zinc-100 rounded-xl px-3 py-1.5 text-center">Agotado</span>
                            @endif
                        </div>
                    </div>

                </article>
            @endforeach
        </div>

        <script>
        if (typeof window.cambiarImagenCard !== 'function') {
            window.cambiarImagenCard = function(btn, imgId, newSrc, subId, formId) {
                if (!newSrc || newSrc.trim() === '') return;

                const imgEl = document.getElementById(imgId);
                if (imgEl) {
                    imgEl.style.transition = 'opacity 0.2s ease-in-out, transform 0.2s ease-in-out';
                    imgEl.style.opacity = '0.3';
                    imgEl.style.transform = 'scale(0.97)';

                    setTimeout(() => {
                        imgEl.src = newSrc;
                        imgEl.style.opacity = '1';
                        imgEl.style.transform = 'scale(1)';
                    }, 150);
                }

                const secImgEl = document.getElementById('sec-' + imgId);
                if (secImgEl) {
                    secImgEl.style.transition = 'opacity 0.2s ease-in-out';
                    secImgEl.style.opacity = '0.3';
                    setTimeout(() => {
                        secImgEl.src = newSrc;
                        secImgEl.style.opacity = '';
                    }, 150);
                }

                if (formId) {
                    const formEl = document.getElementById(formId);
                    if (formEl) {
                        const subInput = formEl.querySelector('input[name="subarticulo_id"]');
                        if (subInput) subInput.value = subId;
                        formEl.setAttribute('data-img', newSrc);
                    }
                }

                if (btn) {
                    const parent = btn.closest('.flex') || btn.parentElement;
                    if (parent) {
                        parent.querySelectorAll('.btn-var-thumb').forEach(b => {
                            b.classList.remove('border-[#88674B]', 'border-amber-700', 'border-amber-800', 'ring-2', 'ring-[#88674B]/30', 'ring-[#88674B]/20', 'ring-amber-700/20', 'scale-105');
                            b.classList.add('border-zinc-200');
                        });
                        btn.classList.remove('border-zinc-200');
                        btn.classList.add('border-[#88674B]', 'ring-2', 'ring-[#88674B]/30', 'scale-105');
                    }
                }
            };
        }
        </script>

        {{-- Paginación --}}
        @if($productos->hasPages())
            <div class="mt-10">
                {{ $productos->links() }}
            </div>
        @endif

    @endif

</div>
