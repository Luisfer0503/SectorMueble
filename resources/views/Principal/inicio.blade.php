@extends('layouts.app')

@section('titulo', 'Sector Mueble | Muebles de Diseño para tu Hogar')

@section('contenido')
    <!-- ── 1. HERO BANNER PRINCIPAL LLAMATIVO A PANTALLA COMPLETA ── -->
    <div id="hero-slider" class="relative bg-[#0B0A0A] overflow-hidden min-h-[580px] sm:min-h-[640px] lg:min-h-[680px] flex items-center justify-center border-b border-[#88674B]/20">
        
        <!-- Imagen de Fondo Llamativa de Alta Resolución -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('inicio.png') }}" alt="Colección de Muebles de Autor Sector Mueble" class="w-full h-full object-cover object-center brightness-[1.02] contrast-[1.02] transition-transform duration-1000">
            <!-- Overlay Ultra Transparente para Máxima Visibilidad de la Foto -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/10 to-black/15"></div>
        </div>

        <!-- Contenido Centrado Enfrente -->
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 sm:py-28 text-center w-full">
            
            <!-- Sub-badge Superior Centrado -->
            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-[#88674B]/80 border border-[#FAF3E0]/30 text-white text-[11px] sm:text-xs font-extrabold uppercase tracking-widest shadow-lg mb-6 backdrop-blur-md">
                <span>Colección Editorial 2026</span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#FAF3E0] animate-pulse"></span>
            </div>

            <!-- Título Principal con Cápsula Ultra Transparente -->
            <h1 class="serif-title text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-tight">
                <span class="inline-block bg-black/5 backdrop-blur-xs px-6 sm:px-8 py-3.5 sm:py-4 rounded-3xl border border-white/20 shadow-md">
                    La belleza de la simplicidad <span style="color: #4c6f4f;" class="font-extrabold drop-shadow-sm">en tu hogar</span>
                </span>
            </h1>

            <!-- Subtítulo Exclusivo Centrado -->
            <p class="mt-6 text-sm sm:text-base lg:text-xl text-zinc-100/95 max-w-2xl mx-auto font-normal leading-relaxed drop-shadow-md">
                Descubre nuestra colección exclusiva de muebles minimalistas de autor. Diseños concebidos para transformar tus espacios con elegancia, calidez y confort artesanal.
            </p>

            <!-- Botones Principales -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('catalogo') }}" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2.5 bg-[#4c6f4f] hover:bg-[#3c583e] text-white text-xs sm:text-sm font-bold px-9 py-4 rounded-2xl shadow-2xl hover:shadow-emerald-900/50 transition-all duration-300 transform hover:-translate-y-0.5 border border-white/20">
                    <span>Explorar Catálogo</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="#hotspots-section" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-bold px-8 py-4 rounded-2xl border border-white/30 backdrop-blur-md shadow-lg transition-all duration-300">
                    <span>Inspírate en la Sala</span>
                </a>
            </div>

        </div>

    </div>


    <!-- ── 2. BARRA DE BENEFICIOS Y GARANTÍAS ── -->
    <div class="bg-[#FAF8F5] py-8 sm:py-14 border-b border-[#88674B]/20 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                
                <!-- Envío Gratuito -->
                <div class="hover-lift bg-[#FAF8F5] p-5 sm:p-6 rounded-2xl border border-zinc-300/80 shadow-xs flex items-start space-x-4 group hover:border-[#4A4746] transition-all duration-300">
                    <div class="p-3.5 bg-[#4A4746] text-white rounded-2xl group-hover:bg-[#383534] transition-colors duration-300 shadow-md flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Envío Gratuito Premium</h3>
                        <p class="mt-1 text-xs text-zinc-600 leading-relaxed">Aplica en compras desde $10,000 MXN dentro de la zona de cobertura(Puebla Capital, San Andrés Cholula y San Pedro Cholula).</p>
                    </div>
                </div>

                <!-- Pago Seguro -->
                <div class="hover-lift bg-[#FAF8F5] p-5 sm:p-6 rounded-2xl border border-zinc-300/80 shadow-xs flex items-start space-x-4 group hover:border-[#4A4746] transition-all duration-300">
                    <div class="p-3.5 bg-[#4A4746] text-white rounded-2xl group-hover:bg-[#383534] transition-colors duration-300 shadow-md flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Pago 100% Seguro</h3>
                        <p class="mt-1 text-xs text-zinc-600 leading-relaxed">Encriptación bancaria SSL y meses sin intereses en tarjetas elegibles.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ── 3. BANNER DE OFERTA FLASH CON TELÓN CINE A PANTALLA COMPLETA ── -->
    <div id="telon-promo-section" class="relative w-full overflow-hidden bg-zinc-950 min-h-[400px] sm:min-h-[550px] lg:min-h-[650px] flex items-center justify-center border-y border-white/20 shadow-2xl">
        
        <!-- ── 1. FONDO CON LA IMAGEN PROMO.PNG COMPLETA SIN RECORTAR ── -->
        <div class="absolute inset-0 z-0 flex items-center justify-center bg-zinc-950 w-full h-full overflow-hidden">
            <!-- Imagen Promo Grande Completa con zoom progresivo sutil -->
            <img id="promo-img-bg" src="{{ asset('promo.png') }}" alt="Promoción Especial de Septiembre" class="w-full h-full object-contain object-center filter brightness-[1.02] contrast-[1.02] transform scale-105 transition-transform duration-[3200ms] ease-out">
            
            <!-- Shadow Overlay sutil para potenciar el contraste -->
            <div class="absolute inset-0 bg-black/20 pointer-events-none"></div>
        </div>

        <!-- ── 2. TICKER CONTADOR DE SEPTIEMBRE (PEQUEÑO ABAJO EN MÓVIL / A 1/4 A LA DERECHA EN DESKTOP) ── -->
        <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 w-[calc(100%-1.25rem)] max-w-sm sm:w-auto sm:max-w-none sm:left-auto sm:right-8 lg:right-12 sm:translate-x-0 sm:bottom-[22%] z-20 pointer-events-auto">
            <div class="flex flex-col items-center lg:items-end bg-black/85 backdrop-blur-md p-2.5 sm:p-5 rounded-2xl sm:rounded-3xl border border-[#FAF3E0]/40 shadow-[0_10px_35px_rgba(0,0,0,0.7)] hover:border-[#FAF3E0] transition-colors duration-500">
                <span class="text-[10px] sm:text-xs font-extrabold uppercase tracking-widest text-[#FAF3E0] mb-1.5 sm:mb-2.5 text-center lg:text-right drop-shadow">
                    ⏳ La oferta termina al finalizar Septiembre:
                </span>
                
                <div class="flex items-center space-x-1.5 sm:space-x-2.5 text-center">
                    <div class="bg-white/95 border border-white/40 p-1.5 sm:p-3 rounded-xl sm:rounded-2xl w-12 sm:w-18 shadow-2xl backdrop-blur-md">
                        <span id="flash-days" class="block text-base sm:text-2xl font-extrabold text-[#88432A] font-mono">15</span>
                        <span class="text-[7px] sm:text-[10px] font-bold uppercase tracking-wider text-zinc-700">Días</span>
                    </div>
                    <span class="text-base sm:text-2xl font-bold text-[#FAF3E0] animate-pulse">:</span>
                    <div class="bg-white/95 border border-white/40 p-1.5 sm:p-3 rounded-xl sm:rounded-2xl w-12 sm:w-18 shadow-2xl backdrop-blur-md">
                        <span id="flash-hours" class="block text-base sm:text-2xl font-extrabold text-[#88432A] font-mono">11</span>
                        <span class="text-[7px] sm:text-[10px] font-bold uppercase tracking-wider text-zinc-700">Horas</span>
                    </div>
                    <span class="text-base sm:text-2xl font-bold text-[#FAF3E0] animate-pulse">:</span>
                    <div class="bg-white/95 border border-white/40 p-1.5 sm:p-3 rounded-xl sm:rounded-2xl w-12 sm:w-18 shadow-2xl backdrop-blur-md">
                        <span id="flash-minutes" class="block text-base sm:text-2xl font-extrabold text-[#88432A] font-mono">41</span>
                        <span class="text-[7px] sm:text-[10px] font-bold uppercase tracking-wider text-zinc-700">Minutos</span>
                    </div>
                    <span class="text-base sm:text-2xl font-bold text-[#FAF3E0] animate-pulse">:</span>
                    <div class="bg-white/95 border border-white/40 p-1.5 sm:p-3 rounded-xl sm:rounded-2xl w-12 sm:w-18 shadow-2xl backdrop-blur-md">
                        <span id="flash-seconds" class="block text-base sm:text-2xl font-extrabold text-[#88432A] font-mono">57</span>
                        <span class="text-[7px] sm:text-[10px] font-bold uppercase tracking-wider text-zinc-700">Segundos</span>
                    </div>
                </div>
                
                <div class="mt-2 sm:mt-3 flex items-center justify-between w-full sm:w-auto gap-2 sm:gap-3">
                    <span class="text-[9px] sm:text-[10px] text-white/90 font-semibold bg-black/50 px-2 sm:px-2.5 py-0.5 rounded-full border border-white/20">Hasta 30 Sept</span>
                    <button type="button" onclick="cerrarTelonSplit()" class="text-[10px] sm:text-xs font-bold text-[#FAF3E0] hover:text-white underline cursor-pointer flex items-center gap-1">
                        <span>Cerrar Telón</span> 🎭
                    </button>
                </div>
            </div>
        </div>

        <!-- ── 3. TELÓN SPLIT CON BRILLO DE ORILLAS EN BEIGE CÁLIDO Y TRANSICIÓN PAUSADA (3.2 SEGUNDOS) ── -->
        <div id="telon-curtain-left" class="absolute inset-y-0 left-0 w-1/2 bg-[#88432A] border-r-4 border-[#FAF3E0]/90 shadow-[10px_0_40px_rgba(250,243,224,0.3)] z-30 transition-transform duration-[3200ms] ease-[cubic-bezier(0.16,1,0.3,1)]" style="background: linear-gradient(90deg, #3B1B10 0%, #88432A 35%, #582818 70%, #A85032 100%);">
            <div class="absolute inset-0 bg-[repeating-linear-gradient(90deg,transparent,transparent_25px,rgba(0,0,0,0.18)_25px,rgba(0,0,0,0.18)_50px)] pointer-events-none"></div>
            <!-- Filamento de Luz Beige Elegante -->
            <div class="absolute inset-y-0 right-0 w-1.5 bg-gradient-to-b from-white via-[#FAF3E0] to-[#E6D7C3] shadow-[0_0_15px_#FAF3E0]"></div>
        </div>

        <div id="telon-curtain-right" class="absolute inset-y-0 right-0 w-1/2 bg-[#88432A] border-l-4 border-[#FAF3E0]/90 shadow-[-10px_0_40px_rgba(250,243,224,0.3)] z-30 transition-transform duration-[3200ms] ease-[cubic-bezier(0.16,1,0.3,1)]" style="background: linear-gradient(270deg, #3B1B10 0%, #88432A 35%, #582818 70%, #A85032 100%);">
            <div class="absolute inset-0 bg-[repeating-linear-gradient(270deg,transparent,transparent_25px,rgba(0,0,0,0.18)_25px,rgba(0,0,0,0.18)_50px)] pointer-events-none"></div>
            <!-- Filamento de Luz Beige Elegante -->
            <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b from-white via-[#FAF3E0] to-[#E6D7C3] shadow-[0_0_15px_#FAF3E0]"></div>
        </div>

        <!-- ── BOTÓN Y TEXTO EN EL CENTRO DEL TELÓN CERRADO ── -->
        <div id="telon-trigger-wrapper" class="absolute inset-0 z-40 flex flex-col items-center justify-center p-4 transition-all duration-700 ease-out">
            <div class="text-center bg-[#88432A]/95 p-6 sm:p-12 rounded-3xl border-2 border-[#FAF3E0]/60 shadow-2xl backdrop-blur-md max-w-2xl mx-auto transform hover:scale-102 transition-transform">
                <span class="inline-flex items-center space-x-2 text-xs font-extrabold uppercase tracking-widest text-white bg-white/20 border border-white/30 px-4 py-1.5 rounded-full mb-4 shadow-md">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FAF3E0] animate-ping"></span>
                    <span>Venta Especial de Septiembre 2026</span>
                </span>

                <h2 class="serif-title text-2xl sm:text-5xl font-bold leading-tight text-white drop-shadow-md">
                    Desliza hacia abajo para descubrir la promoción
                </h2>
                <p class="mt-3 text-white/95 text-xs sm:text-base font-medium max-w-md mx-auto leading-relaxed">
                    Al desplazarte por la página, el telón se abrirá automáticamente para mostrar nuestra oferta exclusiva a pantalla completa.
                </p>

                <button type="button" onclick="abrirTelonSplit()" class="mt-6 sm:mt-8 inline-flex items-center space-x-3 bg-[#FAF3E0] text-[#88432A] hover:bg-white hover:scale-108 text-sm sm:text-base font-extrabold px-7 sm:px-9 py-3.5 sm:py-4.5 rounded-2xl shadow-2xl transition-all duration-300 transform active:scale-95 border-2 border-white/80 cursor-pointer group">
                    <span>Abrir Telón y Descubrir Oferta</span>
                </button>
            </div>
        </div>

    </div>


    <!-- ── 4. CATEGORÍAS "INSPIRACIÓN POR ESTANCIAS" ── -->
    <div class="py-20 sm:py-28 bg-white border-b border-zinc-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#5C4033] bg-[#F5EBE0] border border-[#D9C5B2] px-4 py-1.5 rounded-full">Espacios Inspiradores</span>
                <h2 class="serif-title text-3xl sm:text-5xl font-bold text-zinc-950 mt-3">Inspiración por Estancias</h2>
                <p class="mt-3 text-zinc-600 text-sm sm:text-base">Amuebla cada ambiente con piezas concebidas para coordinar perfectamente entre sí.</p>
            </div>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Salas -->
                <a href="{{ route('catalogo', ['categoria' => 'Salas']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="{{ asset('SALAS.png') }}" alt="Salas" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-[#FAF3E0] uppercase tracking-widest">Estancia Salas</span>
                        <h3 class="text-2xl font-bold mt-1 text-white group-hover:text-[#FAF3E0] transition-colors">Salas</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#FAF3E0] group-hover:text-white group-hover:translate-x-1.5 transition-all">
                            Explorar salas &rarr;
                        </span>
                    </div>
                </a>

                <!-- Recámaras -->
                <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="{{ asset('recamara.png') }}" alt="Recámaras" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-[#FAF3E0] uppercase tracking-widest">Estancia Recámaras</span>
                        <h3 class="text-2xl font-bold mt-1 text-white group-hover:text-[#FAF3E0] transition-colors">Recámaras</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#FAF3E0] group-hover:text-white group-hover:translate-x-1.5 transition-all">
                            Explorar recámaras &rarr;
                        </span>
                    </div>
                </a>

                <!-- Comedor -->
                <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="{{ asset('Comedor.png') }}" alt="Comedor" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-[#FAF3E0] uppercase tracking-widest">Estancia Comedor</span>
                        <h3 class="text-2xl font-bold mt-1 text-white group-hover:text-[#FAF3E0] transition-colors">Comedor</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#FAF3E0] group-hover:text-white group-hover:translate-x-1.5 transition-all">
                            Explorar comedor &rarr;
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>


    <!-- ── 5. SECCIÓN INTERACTIVA "SHOP THE LOOK / HOTSPOTS" (BOUTIQUE CLARA) ── -->
    <div id="hotspots-section" class="py-20 sm:py-28 bg-[#FAF8F4] text-zinc-950 border-b border-zinc-200/80 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#74563C] bg-[#FAF3E0] border border-[#E6D7C3] px-3.5 py-1.5 rounded-full">Experiencia Interactiva</span>
                    <h2 class="serif-title text-3xl sm:text-5xl font-bold mt-3">Inspírate en la Estancia Real</h2>
                    <p class="mt-2 text-zinc-600 text-sm sm:text-base font-normal">Pasa el cursor o haz clic sobre los puntos flotantes <span class="text-[#88674B] font-bold">(+)</span> para explorar los muebles destacados de esta sala.</p>
                </div>
            </div>

            <!-- Contenedor Imagen con Hotspots -->
            <div class="relative w-full h-[450px] sm:h-[580px] rounded-3xl overflow-hidden shadow-2xl border border-stone-300/80">
                <img src="{{ asset('imageninicio.png') }}" alt="Sala de Exhibición Interactiva" class="w-full h-full object-cover">

                @php
                    $hotspotsConfig = [
                        ['id' => 39, 'top' => '42%', 'left' => '48%'],
                        ['id' => 47, 'top' => '58%', 'left' => '20%'],
                        ['id' => 22, 'top' => '72%', 'left' => '62%'],
                    ];
                @endphp

                @foreach($hotspotsConfig as $hs)
                    @php
                        $prod = isset($productosHotspots) ? $productosHotspots->get($hs['id']) : null;
                    @endphp
                    @if($prod)
                        <!-- Hotspot {{ $prod->nombre }} -->
                        <div class="absolute z-20 group hotspot-item" style="top: {{ $hs['top'] }}; left: {{ $hs['left'] }};">
                            <button type="button" onclick="event.stopPropagation(); window.toggleHotspotCard && window.toggleHotspotCard(this);" aria-label="Ver {{ $prod->nombre }}" class="w-9 h-9 rounded-full bg-[#88674B] text-white flex items-center justify-center font-bold text-lg shadow-2xl animate-pulse hover:scale-125 focus:scale-125 transition-transform border-2 border-white cursor-pointer focus:outline-none">
                                +
                            </button>
                            <!-- Popover Card Clara (Centrada en pantalla en Celular, Flotante en Desktop) -->
                            <div class="max-sm:fixed max-sm:top-1/2 max-sm:left-1/2 max-sm:-translate-x-1/2 max-sm:-translate-y-1/2 max-sm:w-[calc(100vw-2rem)] max-sm:max-w-xs max-sm:z-50 sm:absolute sm:top-auto sm:bottom-12 sm:left-1/2 sm:-translate-x-1/2 sm:translate-y-0 sm:w-64 bg-white/95 backdrop-blur-md border border-amber-900/15 p-4 rounded-2xl shadow-2xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto group-[.active]:opacity-100 group-[.active]:pointer-events-auto transition-all duration-300 transform group-hover:translate-y-0 group-[.active]:translate-y-0 translate-y-2">
                                <span class="text-[10px] font-extrabold text-[#88674B] uppercase tracking-widest">{{ $prod->categoria }}</span>
                                <h4 class="text-sm font-bold text-zinc-950 mt-1 line-clamp-1">{{ $prod->nombre }}</h4>
                                
                                <div class="mt-1 flex items-center gap-1.5 font-mono">
                                    @if($prod->tieneDescuento())
                                        <span class="text-xs text-zinc-400 line-through font-sans">$ {{ number_format($prod->precio, 2, '.', ',') }}</span>
                                        <span class="text-xs text-[#88674B] font-extrabold font-sans">$ {{ number_format($prod->precio_descuento, 2, '.', ',') }} MXN</span>
                                    @else
                                        <span class="text-xs text-[#88674B] font-extrabold font-sans">$ {{ number_format($prod->precio, 2, '.', ',') }} MXN</span>
                                    @endif
                                </div>

                                <a href="{{ route('productos.detalle', $prod->id) }}" class="mt-3 block text-center bg-[#88674B] hover:bg-[#74563C] text-white text-xs font-bold py-2 rounded-xl transition-colors shadow-xs">
                                    Ver Producto
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <script>
    (function() {
        window.toggleHotspotCard = function(btn) {
            const parent = btn.closest('.hotspot-item');
            if (!parent) return;
            const isCurrentlyActive = parent.classList.contains('active');
            document.querySelectorAll('.hotspot-item').forEach(el => el.classList.remove('active'));
            if (!isCurrentlyActive) {
                parent.classList.add('active');
            }
        };

        // Al deslizar/scrollear la pantalla, ocultar automáticamente cualquier hotspot abierto
        window.addEventListener('scroll', function() {
            document.querySelectorAll('.hotspot-item').forEach(el => el.classList.remove('active'));
        }, { passive: true });

        // Al hacer clic fuera de cualquier hotspot, ocultar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.hotspot-item')) {
                document.querySelectorAll('.hotspot-item').forEach(el => el.classList.remove('active'));
            }
        });
    })();
    </script>


    <!-- ── 6. SHOWCASE DE PRODUCTOS DESTACADOS CON PESTAÑAS ── -->
    <div id="destacados" class="py-20 sm:py-28 bg-white border-b border-zinc-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-zinc-200/80 pb-6 gap-6">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#74563C] bg-[#FAF3E0] border border-[#E6D7C3] px-3.5 py-1.5 rounded-full">Diseños Exclusivos</span>
                    <h2 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950 mt-2">Muebles Destacados</h2>
                    <p class="mt-1 text-zinc-500 text-sm">Nuestras piezas más aclamadas por arquitectos e interioristas.</p>
                </div>
                
                <!-- Pestañas de Filtrado Rápido (Categorías Principales: Salas, Recámaras, Comedor) -->
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#88674B] text-white shadow-md" data-category="todos">
                        Todos
                    </button>
                    <button type="button" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-zinc-100 hover:bg-[#FAF3E0] text-zinc-700 hover:text-[#74563C] border border-zinc-200/80" data-category="Salas">
                        Salas
                    </button>
                    <button type="button" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-zinc-100 hover:bg-[#FAF3E0] text-zinc-700 hover:text-[#74563C] border border-zinc-200/80" data-category="Dormitorio">
                        Recámaras
                    </button>
                    <button type="button" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-zinc-100 hover:bg-[#FAF3E0] text-zinc-700 hover:text-[#74563C] border border-zinc-200/80" data-category="Comedor">
                        Comedor
                    </button>
                </div>
            </div>

            <!-- Products Grid con Tarjetas Rediseñadas de Alta Gama -->
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($productosDestacados as $producto)
                    <div class="product-item group relative bg-white border border-zinc-200/90 rounded-3xl p-3.5 flex flex-col justify-between h-full hover:border-[#88674B]/40 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1.5" data-cat="{{ $producto->categoria }}">
                        
                        <!-- Img Container con Aspecto Proporcional -->
                        <div class="relative w-full h-72 sm:h-80 rounded-2xl bg-zinc-100 overflow-hidden">
                            <!-- Foto 1 (Principal) -->
                            <img id="img-prod-dest-{{ $producto->id }}" src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                            
                            <!-- Foto 2 (Secundaria en Hover) -->
                            @if($producto->imagen_secundaria_url)
                                <img id="sec-img-prod-dest-{{ $producto->id }}" src="{{ $producto->imagen_secundaria_url }}" alt="{{ $producto->nombre }} (Vista alternativa)" class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 group-hover:scale-108 transition-all duration-700 ease-out">
                            @endif
                            
                            <!-- Insignias / Badges -->
                            <div class="absolute top-3 left-3 flex flex-col space-y-1.5 z-10">
                                @if($producto->modelo_3d_url)
                                    <span class="bg-[#FAF3E0] text-[#74563C] border border-[#E6D7C3] text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md flex items-center space-x-1">
                                        <span> 3D DISPONIBLE</span>
                                    </span>
                                @endif
                                @if($producto->tieneDescuento())
                                    <span class="bg-rose-600 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">-{{ $producto->porcentaje_descuento }}% OFERTA</span>
                                @endif
                                <span class="bg-[#88674B] text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">Destacado</span>
                                @if($producto->stock <= 5)
                                    <span class="bg-zinc-900 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">Últimas {{ $producto->stock }} unid.</span>
                                @endif
                            </div>

                            <!-- Botón Ver Detalles (Arriba a la Derecha) -->
                            <a href="{{ route('productos.detalle', $producto->id) }}" class="absolute top-3 right-3 z-20 bg-white/90 hover:bg-[#88674B] text-zinc-900 hover:text-white backdrop-blur-md border border-white/70 text-[11px] font-bold px-3.5 py-1.5 rounded-full shadow-lg transition-all duration-300 flex items-center space-x-1 hover:scale-105">
                                <span>Ver Detalles</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Información del producto -->
                        <div class="p-3 flex-grow flex flex-col justify-between mt-2">
                            <div>
                                <span class="text-[11px] font-bold text-[#88674B] uppercase tracking-widest">{{ $producto->categoria }}</span>
                                <h3 class="text-base font-bold text-zinc-950 mt-1 line-clamp-1">
                                    <a href="{{ route('productos.detalle', $producto->id) }}" class="hover:text-[#88674B] transition-colors">
                                        {{ $producto->nombre }}
                                    </a>
                                </h3>
                                
                                <!-- Calificación -->
                                <div class="flex items-center space-x-1.5 mt-2">
                                    <div class="flex text-[#C49A6C] text-xs">
                                        <svg class="h-4 w-4 fill-current text-[#C49A6C]" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-bold text-zinc-800">{{ number_format($producto->calificacion, 1) }}</span>
                                    <span class="text-xs text-zinc-400 font-medium">(Cliente verificado)</span>
                                </div>


                            </div>
                            
                            <!-- Precio y Botón de Carrito -->
                            <div class="flex items-center justify-between mt-5 pt-3 border-t border-zinc-100">
                                <div class="flex flex-col">
                                    @if($producto->tieneDescuento())
                                        <div class="flex items-center space-x-1.5 leading-tight">
                                            <span class="text-xs text-zinc-400 line-through font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                            <span class="text-xs font-bold text-rose-600 font-sans">(-{{ $producto->porcentaje_descuento }}%)</span>
                                        </div>
                                        <span class="text-lg font-extrabold text-[#74563C] font-sans">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }} <span class="text-xs font-normal text-zinc-500">MXN</span></span>
                                    @else
                                        <span class="text-lg font-extrabold text-zinc-950 font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }} <span class="text-xs font-normal text-zinc-500">MXN</span></span>
                                    @endif
                                </div>
                                
                                <form
                                    id="form-add-dest-{{ $producto->id }}"
                                    action="{{ route('carrito.agregar', $producto->id) }}"
                                    method="POST"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-img="{{ $producto->imagen_url }}"
                                    onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                                    @csrf
                                    <input type="hidden" name="subarticulo_id" value="{{ $producto->detalles ? optional($producto->detalles->where('activo', true)->first())->id : '' }}">
                                    <button type="submit" aria-label="Añadir {{ $producto->nombre }} al carrito" class="p-3 bg-[#FAF3E0] hover:bg-[#88674B] text-[#74563C] hover:text-white rounded-2xl border border-[#E6D7C3] hover:border-transparent transition-all duration-300 shadow-sm hover:shadow-md">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('catalogo') }}" class="inline-flex items-center space-x-2 bg-zinc-900 hover:bg-[#88674B] text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-2xl transition-all shadow-lg hover:shadow-xl">
                    <span>Ver Catálogo Completo (+120 Piezas)</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>







    <!-- ── SCRIPTS INTERACTIVOS SENIOR FRONTEND ── -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ── 1. Hero Slider Logic ──
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.slider-dot');
            const prevBtn = document.getElementById('slider-prev-btn');
            const nextBtn = document.getElementById('slider-next-btn');
            const slider = document.getElementById('hero-slider');

            let currentSlide = 0;
            let slideInterval = null;

            function goToSlide(index) {
                slides.forEach((slide, idx) => {
                    if (idx === index) {
                        slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                        slide.classList.add('opacity-100', 'z-10');
                    } else {
                        slide.classList.remove('opacity-100', 'z-10');
                        slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                    }
                });

                dots.forEach((dot, idx) => {
                    if (idx === index) {
                        dot.classList.remove('w-2.5', 'bg-zinc-300');
                        dot.classList.add('w-8', 'bg-[#88674B]');
                    } else {
                        dot.classList.remove('w-8', 'bg-[#88674B]');
                        dot.classList.add('w-2.5', 'bg-zinc-300');
                    }
                });

                currentSlide = index;
            }

            function nextSlide() {
                const next = (currentSlide + 1) % slides.length;
                goToSlide(next);
            }

            function prevSlide() {
                const prev = (currentSlide - 1 + slides.length) % slides.length;
                goToSlide(prev);
            }

            function startAutoplay() {
                if (!slideInterval) {
                    slideInterval = setInterval(nextSlide, 5500);
                }
            }

            function stopAutoplay() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = null;
                }
            }

            if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); stopAutoplay(); startAutoplay(); });
            if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); stopAutoplay(); startAutoplay(); });

            dots.forEach((dot) => {
                dot.addEventListener('click', (e) => {
                    const slideIndex = parseInt(e.target.getAttribute('data-slide'));
                    goToSlide(slideIndex);
                    stopAutoplay();
                    startAutoplay();
                });
            });

            if (slider) {
                slider.addEventListener('mouseenter', stopAutoplay);
                slider.addEventListener('mouseleave', startAutoplay);
            }

            startAutoplay();


            // ── 2. Flash Sale Split Telón Curtain Reveal & September Countdown ──
            let telonAbiertoPorScroll = false;

            window.abrirTelonSplit = function() {
                const leftCurtain = document.getElementById('telon-curtain-left');
                const rightCurtain = document.getElementById('telon-curtain-right');
                const triggerWrapper = document.getElementById('telon-trigger-wrapper');
                const promoImgBg = document.getElementById('promo-img-bg');

                if (leftCurtain && rightCurtain && triggerWrapper) {
                    leftCurtain.style.transform = 'translateX(-100%)';
                    rightCurtain.style.transform = 'translateX(100%)';
                    triggerWrapper.style.opacity = '0';
                    triggerWrapper.style.pointerEvents = 'none';
                    triggerWrapper.style.transform = 'scale(0.9)';
                    telonAbiertoPorScroll = true;

                    if (promoImgBg) {
                        promoImgBg.classList.remove('scale-105');
                        promoImgBg.classList.add('scale-100');
                    }
                }
            };

            window.cerrarTelonSplit = function() {
                const leftCurtain = document.getElementById('telon-curtain-left');
                const rightCurtain = document.getElementById('telon-curtain-right');
                const triggerWrapper = document.getElementById('telon-trigger-wrapper');
                const promoImgBg = document.getElementById('promo-img-bg');

                if (leftCurtain && rightCurtain && triggerWrapper) {
                    leftCurtain.style.transform = 'translateX(0)';
                    rightCurtain.style.transform = 'translateX(0)';
                    triggerWrapper.style.opacity = '1';
                    triggerWrapper.style.pointerEvents = 'auto';
                    triggerWrapper.style.transform = 'scale(1)';

                    if (promoImgBg) {
                        promoImgBg.classList.remove('scale-100');
                        promoImgBg.classList.add('scale-105');
                    }
                }
            };

            // Apertura automática del telón conforme el usuario va bajando en la página (Scroll Trigger)
            const telonSection = document.getElementById('telon-promo-section');
            if (telonSection && 'IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !telonAbiertoPorScroll) {
                            window.abrirTelonSplit();
                        }
                    });
                }, { threshold: 0.25 });

                observer.observe(telonSection);
            }

            const targetSeptDate = new Date('2026-09-30T23:59:59').getTime();
            const daysEl = document.getElementById('flash-days');
            const hoursEl = document.getElementById('flash-hours');
            const minsEl = document.getElementById('flash-minutes');
            const secsEl = document.getElementById('flash-seconds');

            function updateFlashClock() {
                const now = new Date().getTime();
                let distance = targetSeptDate - now;

                if (distance <= 0) {
                    distance = 15 * 24 * 3600 * 1000; // Reciclar para demostración
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                if (daysEl) daysEl.textContent = String(d).padStart(2, '0');
                if (hoursEl) hoursEl.textContent = String(h).padStart(2, '0');
                if (minsEl) minsEl.textContent = String(m).padStart(2, '0');
                if (secsEl) secsEl.textContent = String(s).padStart(2, '0');
            }

            setInterval(updateFlashClock, 1000);
            updateFlashClock();


            // ── 3. Category Tab Filtering ──
            const tabBtns = document.querySelectorAll('.tab-btn');
            const productItems = document.querySelectorAll('.product-item');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const category = btn.getAttribute('data-category');

                    tabBtns.forEach(b => {
                        b.classList.remove('bg-[#88674B]', 'text-white', 'shadow-md');
                        b.classList.add('bg-zinc-100', 'text-zinc-700', 'border', 'border-zinc-200/80');
                    });

                    btn.classList.remove('bg-zinc-100', 'text-zinc-700', 'border', 'border-zinc-200/80');
                    btn.classList.add('bg-[#88674B]', 'text-white', 'shadow-md');

                    productItems.forEach(item => {
                        const itemCat = item.getAttribute('data-cat');
                        const isMatch = (category === 'todos') ||
                                        (category === itemCat) ||
                                        (category === 'Salas' && itemCat === 'Salas') ||
                                        (category === 'Dormitorio' && (itemCat === 'Recámaras' || itemCat === 'Dormitorio')) ||
                                        (category === 'Comedor' && itemCat === 'Comedor');

                        if (isMatch) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });

        // ── 4. Copy Coupon Code Function ──
        function copyCouponCode() {
            const couponVal = document.getElementById('coupon-code-val')?.innerText || 'SECTOR2026';
            navigator.clipboard.writeText(couponVal).then(() => {
                const copyBtn = document.getElementById('copy-coupon-btn');
                if (copyBtn) {
                    const originalText = copyBtn.innerText;
                    copyBtn.innerText = '¡Copiado! ✓';
                    copyBtn.classList.remove('bg-[#4A4746]');
                    copyBtn.classList.add('bg-emerald-700');
                    setTimeout(() => {
                        copyBtn.innerText = originalText;
                        copyBtn.classList.remove('bg-emerald-700');
                        copyBtn.classList.add('bg-[#4A4746]');
                    }, 2000);
                }
            }).catch(err => console.error('Error al copiar cupón:', err));
        }



        // ── 6. Switcher de Modelos 3D Interactivo ──
        function switch3DModel(modelKey, title, modelUrl, btnEl) {
            const viewer = document.getElementById('furniture-3d-viewer');
            const titleEl = document.getElementById('model-3d-title');
            
            if (viewer) {
                viewer.setAttribute('src', modelUrl);
            }
            if (titleEl) {
                titleEl.textContent = title + ' (3D)';
            }

            const btns = document.querySelectorAll('.model-select-btn');
            btns.forEach(b => {
                b.classList.remove('bg-[#88674B]', 'text-white', 'border-[#88674B]', 'shadow-sm');
                b.classList.add('bg-zinc-100', 'hover:bg-[#FAF3E0]', 'text-zinc-700', 'border-zinc-200/90');
            });

            if (btnEl) {
                btnEl.classList.remove('bg-zinc-100', 'hover:bg-[#FAF3E0]', 'text-zinc-700', 'border-zinc-200/90');
                btnEl.classList.add('bg-[#88674B]', 'text-white', 'border-[#88674B]', 'shadow-sm');
            }
        }
    </script>
@endsection
