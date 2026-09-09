@extends('layouts.app')

@section('titulo', 'Sector Mueble | Muebles de Diseño para tu Hogar')

@section('contenido')
    <!-- ── 1. HERO BANNER PRINCIPAL LLAMATIVO A PANTALLA COMPLETA ── -->
    <div id="hero-slider" class="relative bg-[#0B0A0A] overflow-hidden min-h-[580px] sm:min-h-[640px] lg:min-h-[680px] flex items-center justify-center border-b border-[#88674B]/20">
        
        <!-- Imagen de Fondo Llamativa de Alta Resolución -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('inicio.png') }}" alt="Colección de Muebles de Autor Sector Mueble" class="w-full h-full object-cover object-center brightness-[0.98] contrast-[1.02] transition-transform duration-1000">
            <!-- Overlay Gradiente Suave para Máxima Visibilidad de la Imagen -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-black/35"></div>
        </div>

        <!-- Contenido Centrado Enfrente -->
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center w-full">
            
            <!-- Sub-badge Superior Centrado -->
            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-[#88674B]/90 border border-[#FAF3E0]/30 text-white text-[11px] sm:text-xs font-extrabold uppercase tracking-widest shadow-lg mb-6 backdrop-blur-md">
                <span>✨ Colección Editorial 2026</span>
                <span class="w-1.5 h-1.5 rounded-full bg-amber-300 animate-pulse"></span>
            </div>

            <!-- Título Principal con Cápsula Sutil y Altamente Transparente -->
            <h1 class="serif-title text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-tight">
                <span class="inline-block bg-[#3D271D]/30 backdrop-blur-lg px-6 sm:px-8 py-3.5 sm:py-4 rounded-3xl border border-[#FAF3E0]/25 shadow-xl">
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
                <div class="hover-lift bg-[#FAF8F5] p-5 sm:p-6 rounded-2xl border border-[#88674B]/30 shadow-xs flex items-start space-x-4 group hover:border-[#88674B] transition-all duration-300">
                    <div class="p-3.5 bg-[#74563C] text-white rounded-2xl group-hover:bg-[#88674B] transition-colors duration-300 shadow-md flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Envío Gratuito Premium</h3>
                        <p class="mt-1 text-xs text-zinc-600 leading-relaxed">Aplica en compras desde $10,000 MXN dentro de la zona de cobertura(Puebla Capital, San Andres Cholulas y San Pedro Choula).</p>
                    </div>
                </div>

                <!-- Pago Seguro -->
                <div class="hover-lift bg-[#FAF8F5] p-5 sm:p-6 rounded-2xl border border-[#88674B]/30 shadow-xs flex items-start space-x-4 group hover:border-[#88674B] transition-all duration-300">
                    <div class="p-3.5 bg-[#74563C] text-white rounded-2xl group-hover:bg-[#88674B] transition-colors duration-300 shadow-md flex-shrink-0">
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


    <!-- ── 3. BANNER DE OFERTA FLASH ── -->
    <div class="bg-gradient-to-r from-[#FAF8F5] via-amber-50/80 to-[#FAF8F5] py-12 sm:py-16 text-zinc-950 border-y border-[#88674B]/30 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            
            <div class="text-center lg:text-left max-w-xl">
                <span class="inline-flex items-center space-x-2 text-xs font-extrabold uppercase tracking-widest text-[#74563C] bg-white border border-[#74563C]/40 px-4 py-1.5 rounded-full mb-3 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-[#74563C] animate-ping"></span>
                    <span>Venta Especial Flash 2026</span>
                </span>
                <h2 class="serif-title text-3xl sm:text-5xl font-bold leading-tight text-zinc-950">Últimas Horas: Colección Escandinava</h2>
                <p class="mt-2 text-zinc-700 text-sm sm:text-base font-normal">Obtén un <strong class="text-[#74563C] font-bold">15% EXTRA</strong> en tu carrito aplicando el código exclusivo de temporada.</p>
                
                <!-- Botón Copiar Cupón -->
                <div class="mt-5 inline-flex items-center space-x-2 bg-white p-1.5 pl-4 rounded-2xl border border-[#74563C]/40 shadow-md">
                    <span class="text-xs font-mono font-extrabold tracking-widest text-[#74563C]" id="coupon-code-val">SECTOR2026</span>
                    <button type="button" onclick="copyCouponCode()" id="copy-coupon-btn" class="bg-[#74563C] hover:bg-[#5C4331] text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-xs">
                        Copiar Cupón
                    </button>
                </div>
            </div>

            <!-- Ticker de Reloj / Cuenta Regresiva Claro -->
            <div class="flex items-center space-x-3 sm:space-x-4 text-center">
                <div class="bg-white border border-[#74563C]/30 p-3.5 sm:p-4 rounded-2xl w-18 sm:w-22 shadow-lg">
                    <span id="flash-hours" class="block text-2xl sm:text-4xl font-extrabold text-[#74563C] font-mono">08</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-zinc-500">Horas</span>
                </div>
                <span class="text-2xl sm:text-4xl font-bold text-[#74563C] animate-pulse">:</span>
                <div class="bg-white border border-[#74563C]/30 p-3.5 sm:p-4 rounded-2xl w-18 sm:w-22 shadow-lg">
                    <span id="flash-minutes" class="block text-2xl sm:text-4xl font-extrabold text-[#74563C] font-mono">42</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-zinc-500">Minutos</span>
                </div>
                <span class="text-2xl sm:text-4xl font-bold text-[#74563C] animate-pulse">:</span>
                <div class="bg-white border border-[#74563C]/30 p-3.5 sm:p-4 rounded-2xl w-18 sm:w-22 shadow-lg">
                    <span id="flash-seconds" class="block text-2xl sm:text-4xl font-extrabold text-[#74563C] font-mono">19</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-zinc-500">Segundos</span>
                </div>
            </div>

        </div>
    </div>


    <!-- ── 4. CATEGORÍAS "INSPIRACIÓN POR ESTANCIAS" ── -->
    <div class="py-20 sm:py-28 bg-white border-b border-zinc-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-900 bg-amber-50 border border-amber-200/90 px-4 py-1.5 rounded-full">Espacios Inspiradores</span>
                <h2 class="serif-title text-3xl sm:text-5xl font-bold text-zinc-950 mt-3">Inspiración por Estancias</h2>
                <p class="mt-3 text-zinc-600 text-sm sm:text-base">Amuebla cada ambiente con piezas concebidas para coordinar perfectamente entre sí.</p>
            </div>

            <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Salón -->
                <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="{{ asset('inicio2.png') }}" alt="Salón" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest">Estancia Salón</span>
                        <h3 class="text-2xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Salón</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#4c6f4f] group-hover:text-emerald-300 group-hover:translate-x-1.5 transition-all">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Recámara -->
                <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=700" alt="Dormitorio" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest">Estancia Recámara</span>
                        <h3 class="text-2xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Recámara</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#4c6f4f] group-hover:text-emerald-300 group-hover:translate-x-1.5 transition-all">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Comedor -->
                <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="https://images.unsplash.com/photo-1617806118233-18e1db207f62?q=80&w=700" alt="Comedor" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest">Estancia Comedor</span>
                        <h3 class="text-2xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Comedor</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#4c6f4f] group-hover:text-emerald-300 group-hover:translate-x-1.5 transition-all">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Oficina -->
                <a href="{{ route('catalogo', ['categoria' => 'Oficina']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="https://images.unsplash.com/photo-1505797149-43b0069ec26b?q=80&w=700" alt="Oficina" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest">Estancia Oficina</span>
                        <h3 class="text-2xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Oficina</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#4c6f4f] group-hover:text-emerald-300 group-hover:translate-x-1.5 transition-all">
                            Explorar catálogo &rarr;
                        </span>
                    </div>
                </a>

                <!-- Exterior -->
                <a href="{{ route('catalogo', ['categoria' => 'Exterior']) }}" class="group relative h-84 rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1.5">
                    <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=700" alt="Exterior" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest">Estancia Exterior</span>
                        <h3 class="text-2xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Exterior</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-extrabold text-[#4c6f4f] group-hover:text-emerald-300 group-hover:translate-x-1.5 transition-all">
                            Explorar catálogo &rarr;
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
                    <span class="text-xs font-extrabold uppercase tracking-widest text-amber-900 bg-amber-100/90 border border-amber-200 px-3.5 py-1.5 rounded-full">Experiencia Interactiva</span>
                    <h2 class="serif-title text-3xl sm:text-5xl font-bold mt-3">Inspírate en la Estancia Real</h2>
                    <p class="mt-2 text-zinc-600 text-sm sm:text-base font-normal">Pasa el cursor o haz clic sobre los puntos flotantes <span class="text-amber-800 font-bold">(+)</span> para explorar los muebles destacados de esta sala.</p>
                </div>
            </div>

            <!-- Contenedor Imagen con Hotspots -->
            <div class="relative w-full h-[450px] sm:h-[580px] rounded-3xl overflow-hidden shadow-2xl border border-stone-300/80">
                <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1600" alt="Sala de Exhibición Interactiva" class="w-full h-full object-cover">

                <!-- Hotspot 1: Sofá Principal -->
                <div class="absolute top-[55%] left-[32%] z-20 group">
                    <button type="button" aria-label="Ver Sofá Modular Escandinavo" class="w-9 h-9 rounded-full bg-amber-800 text-white flex items-center justify-center font-bold text-lg shadow-2xl animate-pulse hover:scale-125 transition-transform border-2 border-white">
                        +
                    </button>
                    <!-- Popover Card Clara -->
                    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 w-64 bg-white/95 backdrop-blur-md border border-amber-900/15 p-4 rounded-2xl shadow-2xl opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                        <span class="text-[10px] font-extrabold text-amber-900 uppercase tracking-widest">Salón</span>
                        <h4 class="text-sm font-bold text-zinc-950 mt-1">Sofá Modular Nordik 3 Cuerpos</h4>
                        <p class="text-xs text-amber-950 mt-1 font-mono font-extrabold">$ 24,900.00 MXN</p>
                        <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="mt-3 block text-center bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold py-2 rounded-xl transition-colors shadow-xs">
                            Ver en Catálogo
                        </a>
                    </div>
                </div>

                <!-- Hotspot 2: Mesa de Centro Madera Noble -->
                <div class="absolute top-[68%] left-[62%] z-20 group">
                    <button type="button" aria-label="Ver Mesa de Centro Roble" class="w-9 h-9 rounded-full bg-amber-800 text-white flex items-center justify-center font-bold text-lg shadow-2xl animate-pulse hover:scale-125 transition-transform border-2 border-white">
                        +
                    </button>
                    <!-- Popover Card Clara -->
                    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 w-64 bg-white/95 backdrop-blur-md border border-amber-900/15 p-4 rounded-2xl shadow-2xl opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                        <span class="text-[10px] font-extrabold text-amber-900 uppercase tracking-widest">Salón</span>
                        <h4 class="text-sm font-bold text-zinc-950 mt-1">Mesa de Centro Roble Macizo</h4>
                        <p class="text-xs text-amber-950 mt-1 font-mono font-extrabold">$ 8,450.00 MXN</p>
                        <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="mt-3 block text-center bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold py-2 rounded-xl transition-colors shadow-xs">
                            Ver en Catálogo
                        </a>
                    </div>
                </div>

                <!-- Hotspot 3: Lámpara de Pie Escultural -->
                <div class="absolute top-[35%] left-[82%] z-20 group">
                    <button type="button" aria-label="Ver Lámpara de Pie" class="w-9 h-9 rounded-full bg-amber-800 text-white flex items-center justify-center font-bold text-lg shadow-2xl animate-pulse hover:scale-125 transition-transform border-2 border-white">
                        +
                    </button>
                    <!-- Popover Card Clara -->
                    <div class="absolute top-12 left-1/2 -translate-x-1/2 w-64 bg-white/95 backdrop-blur-md border border-amber-900/15 p-4 rounded-2xl shadow-2xl opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2">
                        <span class="text-[10px] font-extrabold text-amber-900 uppercase tracking-widest">Iluminación</span>
                        <h4 class="text-sm font-bold text-zinc-950 mt-1">Lámpara Escultura Arce</h4>
                        <p class="text-xs text-amber-950 mt-1 font-mono font-extrabold">$ 5,200.00 MXN</p>
                        <a href="{{ route('catalogo') }}" class="mt-3 block text-center bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold py-2 rounded-xl transition-colors shadow-xs">
                            Ver en Catálogo
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ── 6. SHOWCASE DE PRODUCTOS DESTACADOS CON PESTAÑAS ── -->
    <div id="destacados" class="py-20 sm:py-28 bg-white border-b border-zinc-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-zinc-200/80 pb-6 gap-6">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-amber-900 bg-amber-50 border border-amber-200/80 px-3.5 py-1.5 rounded-full">Diseños Exclusivos</span>
                    <h2 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950 mt-2">Muebles Destacados</h2>
                    <p class="mt-1 text-zinc-500 text-sm">Nuestras piezas más aclamadas por arquitectos e interioristas.</p>
                </div>
                
                <!-- Pestañas de Filtrado Rápido -->
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-amber-800 text-white shadow-md" data-category="todos">
                        Todos
                    </button>
                    @foreach($categorias as $cat)
                        <button type="button" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-zinc-100 hover:bg-amber-50 text-zinc-700 hover:text-amber-900 border border-zinc-200/80" data-category="{{ $cat }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid con Tarjetas Rediseñadas de Alta Gama -->
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($productosDestacados as $producto)
                    <div class="product-item group relative bg-white border border-zinc-200/90 rounded-3xl p-3.5 flex flex-col justify-between h-full hover:border-amber-700/40 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1.5" data-cat="{{ $producto->categoria }}">
                        
                        <!-- Img Container con Aspecto Proporcional -->
                        <div class="relative w-full h-72 sm:h-80 rounded-2xl bg-zinc-100 overflow-hidden">
                            <!-- Foto 1 (Principal) -->
                            <img id="img-prod-dest-{{ $producto->id }}" src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                            
                            <!-- Foto 2 (Secundaria en Hover) -->
                            @if($producto->imagen_secundaria_url)
                                <img src="{{ $producto->imagen_secundaria_url }}" alt="{{ $producto->nombre }} (Vista alternativa)" class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 group-hover:scale-108 transition-all duration-700 ease-out">
                            @endif
                            
                            <!-- Insignias / Badges -->
                            <div class="absolute top-3 left-3 flex flex-col space-y-1.5 z-10">
                                @if($producto->modelo_3d_url)
                                    <span class="bg-amber-500 text-zinc-950 text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md flex items-center space-x-1">
                                        <span>🎮 3D DISPONIBLE</span>
                                    </span>
                                @endif
                                @if($producto->tieneDescuento())
                                    <span class="bg-rose-600 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">-{{ $producto->porcentaje_descuento }}% OFERTA</span>
                                @endif
                                <span class="bg-amber-800 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">Destacado</span>
                                @if($producto->stock <= 5)
                                    <span class="bg-zinc-900 text-white text-[10px] font-extrabold px-2.5 py-1 uppercase rounded-lg tracking-wider shadow-md">Últimas {{ $producto->stock }} unid.</span>
                                @endif
                            </div>

                            <!-- Botón de Vista Rápida en Hover -->
                            <div class="absolute inset-0 bg-zinc-950/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                <a href="{{ route('productos.detalle', $producto->id) }}" class="bg-white/95 text-zinc-950 text-xs font-bold px-5 py-3 rounded-xl shadow-xl hover:bg-amber-800 hover:text-white transition-all transform group-hover:scale-100 scale-95">
                                    Ver Ficha Técnica
                                </a>
                            </div>
                        </div>

                        <!-- Información del producto -->
                        <div class="p-3 flex-grow flex flex-col justify-between mt-2">
                            <div>
                                <span class="text-[11px] font-bold text-amber-800 uppercase tracking-widest">{{ $producto->categoria }}</span>
                                <h3 class="text-base font-bold text-zinc-950 mt-1 line-clamp-1">
                                    <a href="{{ route('productos.detalle', $producto->id) }}" class="hover:text-amber-800 transition-colors">
                                        {{ $producto->nombre }}
                                    </a>
                                </h3>
                                
                                <!-- Calificación -->
                                <div class="flex items-center space-x-1.5 mt-2">
                                    <div class="flex text-amber-500 text-xs">
                                        <svg class="h-4 w-4 fill-current text-amber-500" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-bold text-zinc-800">{{ number_format($producto->calificacion, 1) }}</span>
                                    <span class="text-xs text-zinc-400 font-medium">(Cliente verificado)</span>
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
                                                    onclick="cambiarImagenCard(this, 'img-prod-dest-{{ $producto->id }}', '{{ $det->imagen_url }}', '{{ $det->id }}', 'form-add-dest-{{ $producto->id }}')"
                                                    class="btn-var-thumb relative w-7 h-7 sm:w-8 sm:h-8 rounded-lg overflow-hidden border-2 transition-all duration-200 shrink-0 focus:outline-none {{ $idx === 0 ? 'border-amber-700 ring-2 ring-amber-700/20' : 'border-zinc-200 hover:border-amber-500' }}">
                                                    <img src="{{ $det->imagen_url }}" alt="{{ $det->nombre }}" class="w-full h-full object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Precio y Botón de Carrito -->
                            <div class="flex items-center justify-between mt-5 pt-3 border-t border-zinc-100">
                                <div class="flex flex-col">
                                    @if($producto->tieneDescuento())
                                        <span class="text-xs text-zinc-400 line-through font-sans">$ {{ number_format($producto->precio, 2, '.', ',') }}</span>
                                        <span class="text-lg font-extrabold text-amber-900 font-sans">$ {{ number_format($producto->precio_descuento, 2, '.', ',') }} <span class="text-xs font-normal text-zinc-500">MXN</span></span>
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
                                    <input type="hidden" name="subarticulo_id" value="{{ $detallesActivos->first()->id ?? '' }}">
                                    <button type="submit" aria-label="Añadir {{ $producto->nombre }} al carrito" class="p-3 bg-amber-50 hover:bg-amber-800 text-amber-900 hover:text-white rounded-2xl border border-amber-200/80 hover:border-transparent transition-all duration-300 shadow-sm hover:shadow-md">
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
                <a href="{{ route('catalogo') }}" class="inline-flex items-center space-x-2 bg-zinc-900 hover:bg-amber-800 text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-2xl transition-all shadow-lg hover:shadow-xl">
                    <span>Ver Catálogo Completo (+120 Piezas)</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>





    <!-- ── 8. BANNER DE PRIVILEGIOS / NEWSLETTER ── -->
    <div class="bg-[#0B0A0A] text-white py-20 sm:py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-[#8C4D70] p-8 sm:p-14 rounded-3xl border border-white/20 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-xl text-center lg:text-left">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#FAF3E0] bg-white/20 border border-white/30 px-3.5 py-1.5 rounded-full">Club de Miembros VIP</span>
                    <h2 class="serif-title text-3xl sm:text-4xl font-bold mt-3 text-white">Recibe $500 MXN en tu Primera Compra</h2>
                    <p class="mt-2 text-[#FAF3E0]/90 text-sm sm:text-base font-light">Suscríbete para recibir lanzamientos privados, catálogos digitales exclusivos y promociones de temporada antes que nadie.</p>
                </div>

                <form onsubmit="handleNewsletterSubmit(event)" class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                    <input type="email" id="newsletter-email" required placeholder="Tu correo electrónico..." class="px-5 py-3.5 rounded-2xl bg-black/25 border border-white/30 text-white text-sm placeholder-white/70 focus:outline-none focus:border-white/60 w-full sm:w-80 shadow-inner">
                    <button type="submit" class="bg-[#4c6f4f] hover:bg-[#3c583e] text-white text-xs font-bold uppercase tracking-wider px-8 py-4 rounded-2xl transition-all shadow-xl whitespace-nowrap border border-white/20 active:scale-95">
                        Unirme al Club
                    </button>
                </form>
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
                        dot.classList.add('w-8', 'bg-amber-800');
                    } else {
                        dot.classList.remove('w-8', 'bg-amber-800');
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


            // ── 2. Flash Sale Countdown Timer ──
            const hoursEl = document.getElementById('flash-hours');
            const minsEl = document.getElementById('flash-minutes');
            const secsEl = document.getElementById('flash-seconds');

            let totalSeconds = (8 * 3600) + (42 * 60) + 19;

            function updateFlashClock() {
                if (totalSeconds <= 0) {
                    totalSeconds = 8 * 3600; // Reset loop for demo
                }
                totalSeconds--;

                const h = Math.floor(totalSeconds / 3600);
                const m = Math.floor((totalSeconds % 3600) / 60);
                const s = totalSeconds % 60;

                if (hoursEl) hoursEl.textContent = String(h).padStart(2, '0');
                if (minsEl) minsEl.textContent = String(m).padStart(2, '0');
                if (secsEl) secsEl.textContent = String(s).padStart(2, '0');
            }

            setInterval(updateFlashClock, 1000);


            // ── 3. Category Tab Filtering ──
            const tabBtns = document.querySelectorAll('.tab-btn');
            const productItems = document.querySelectorAll('.product-item');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const category = btn.getAttribute('data-category');

                    tabBtns.forEach(b => {
                        b.classList.remove('bg-amber-800', 'text-white', 'shadow-md');
                        b.classList.add('bg-zinc-100', 'text-zinc-700', 'border', 'border-zinc-200/80');
                    });

                    btn.classList.remove('bg-zinc-100', 'text-zinc-700', 'border', 'border-zinc-200/80');
                    btn.classList.add('bg-amber-800', 'text-white', 'shadow-md');

                    productItems.forEach(item => {
                        const itemCat = item.getAttribute('data-cat');
                        if (category === 'todos' || itemCat === category) {
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
                    copyBtn.classList.remove('bg-amber-800');
                    copyBtn.classList.add('bg-emerald-700');
                    setTimeout(() => {
                        copyBtn.innerText = originalText;
                        copyBtn.classList.remove('bg-emerald-700');
                        copyBtn.classList.add('bg-amber-800');
                    }, 2000);
                }
            }).catch(err => console.error('Error al copiar cupón:', err));
        }

        // ── 5. Newsletter Submission Handler ──
        function handleNewsletterSubmit(e) {
            e.preventDefault();
            const emailInput = document.getElementById('newsletter-email');
            if (emailInput && emailInput.value) {
                alert('¡Gracias por unirte al Club Sector Mueble! Te hemos enviado tu código de $500 MXN de regalo a ' + emailInput.value);
                emailInput.value = '';
            }
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
                b.classList.remove('bg-amber-800', 'text-white', 'border-amber-700', 'shadow-sm');
                b.classList.add('bg-zinc-100', 'hover:bg-amber-50', 'text-zinc-700', 'border-zinc-200/90');
            });

            if (btnEl) {
                btnEl.classList.remove('bg-zinc-100', 'hover:bg-amber-50', 'text-zinc-700', 'border-zinc-200/90');
                btnEl.classList.add('bg-amber-800', 'text-white', 'border-amber-700', 'shadow-sm');
            }
        }
    </script>
@endsection
