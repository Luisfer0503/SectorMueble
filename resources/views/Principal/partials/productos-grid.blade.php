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

        {{-- Grid de productos --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($productos as $producto)
                <article class="group relative bg-white flex flex-col border border-zinc-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">

                    {{-- Imagen --}}
                    <div class="relative w-full h-64 bg-zinc-100 overflow-hidden">
                        <img
                            src="{{ $producto->imagen_url }}"
                            alt="{{ $producto->nombre }}"
                            loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >

                        {{-- Badges --}}
                        <div class="absolute top-3 left-3 flex flex-col space-y-1">
                            @if($producto->tieneDescuento())
                                <span class="inline-flex items-center gap-1 bg-rose-600 text-white text-[9px] font-bold px-2 py-0.5 uppercase rounded-full tracking-wider shadow">
                                    -{{ $producto->porcentaje_descuento }}% OFERTA
                                </span>
                            @endif
                            @if($producto->destacado)
                                <span class="inline-flex items-center gap-1 bg-amber-800 text-white text-[9px] font-bold px-2 py-0.5 uppercase rounded-full tracking-wider shadow">
                                    ★ Destacado
                                </span>
                            @endif
                            @if($producto->stock > 0 && $producto->stock <= 5)
                                <span class="inline-flex items-center gap-1 bg-orange-500 text-white text-[9px] font-bold px-2 py-0.5 uppercase rounded-full tracking-wider shadow">
                                    Últimas {{ $producto->stock }}
                                </span>
                            @endif
                        </div>

                        {{-- Hover overlay con botón Ver Detalles --}}
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <a href="{{ route('productos.detalle', $producto->id) }}"
                               class="bg-white text-zinc-900 text-xs font-bold px-5 py-2.5 rounded-full shadow-lg hover:bg-amber-800 hover:text-white transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                Ver Detalles
                            </a>
                        </div>
                    </div>

                    {{-- Info del producto --}}
                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <span class="text-[9px] text-zinc-400 font-bold uppercase tracking-wider">{{ $producto->categoria }}</span>
                            <h3 class="text-sm font-semibold text-zinc-900 mt-0.5 leading-snug">
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
                                <span class="text-[10px] text-zinc-400 font-medium">{{ number_format($producto->calificacion, 1) }}</span>
                            </div>
                        </div>

                        {{-- Precio y botón Añadir --}}
                        <div class="flex items-end justify-between mt-4 pt-3 border-t border-zinc-100">
                            <div class="flex flex-col">
                                @if($producto->tieneDescuento())
                                    <span class="text-[10px] text-zinc-400 line-through font-sans leading-tight">
                                        $ {{ number_format($producto->precio, 2, '.', ',') }}
                                    </span>
                                    <span class="text-sm font-bold text-emerald-700 font-sans leading-tight">
                                        $ {{ number_format($producto->precio_descuento, 2, '.', ',') }}
                                        <span class="text-[9px] font-bold text-rose-600">(-{{ $producto->porcentaje_descuento }}%)</span>
                                    </span>
                                @else
                                    <span class="text-sm font-bold text-zinc-900 font-sans">
                                        $ {{ number_format($producto->precio, 2, '.', ',') }}
                                    </span>
                                @endif
                                <span class="text-[9px] text-zinc-400">MXN</span>
                            </div>

                            @if($producto->stock > 0)
                                <form
                                    action="{{ route('carrito.agregar', $producto->id) }}"
                                    method="POST"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-img="{{ $producto->imagen_url }}"
                                    onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center space-x-1.5 text-xs bg-zinc-900 hover:bg-amber-800 text-white rounded-full px-3.5 py-2 transition-all duration-300 shadow-sm hover:shadow-md group/btn">
                                        <svg class="h-3 w-3 transition-transform group-hover/btn:rotate-90 duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>Añadir</span>
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider bg-zinc-100 rounded-full px-3 py-1.5">Agotado</span>
                            @endif
                        </div>
                    </div>

                </article>
            @endforeach
        </div>

        {{-- Paginación --}}
        @if($productos->hasPages())
            <div class="mt-10">
                {{ $productos->links() }}
            </div>
        @endif

    @endif

</div>
