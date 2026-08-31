@extends('layouts.app')

@section('titulo', 'Sector Mueble | Muebles de Diseño para tu Hogar')

@section('contenido')
    <!-- ── 1. HERO SLIDER INTERACTIVO LUMINOSO DE ALTA GAMA ── -->
    <div id="hero-slider" class="relative bg-gradient-to-b from-[#FAF8F5] via-[#F6F2EB] to-white overflow-hidden min-h-[620px] sm:min-h-[660px] lg:min-h-[720px] flex items-center border-b border-amber-900/10 group">
        
        <!-- Luz ambiental de estudio -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-amber-200/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-10 w-80 h-80 bg-orange-100/50 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Contenedor de Slides -->
        <div class="relative w-full h-full">
            
            <!-- Slide 1 -->
            <div class="hero-slide absolute inset-0 z-10 opacity-100 transition-opacity duration-1000 ease-in-out flex items-center">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1800" alt="Colección Minimalista Sector Mueble" class="w-full h-full object-cover opacity-15 mix-blend-multiply scale-105 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#FAF8F5] via-[#FAF8F5]/90 to-transparent"></div>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 lg:py-24 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        
                        <!-- Columna Izquierda: Textos y CTAs -->
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-amber-100/90 border border-amber-200/90 text-amber-900 text-[10px] sm:text-xs font-extrabold uppercase tracking-widest shadow-xs mb-4 sm:mb-6">
                                <span>✨ Colección Editorial 2026</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                            </div>

                            <h1 class="serif-title text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-zinc-950 leading-[1.10]">
                                La belleza de la simplicidad <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-800 via-amber-700 to-amber-900">en tu hogar</span>
                            </h1>

                            <p class="mt-4 sm:mt-6 text-sm sm:text-base lg:text-lg text-zinc-600 font-normal leading-relaxed">
                                Descubre nuestra colección exclusiva de muebles minimalistas. Interactúa con el modelo 3D a la derecha, gíralo 360° y aprecia cada textura con calidad de estudio.
                            </p>

                            <div class="mt-6 flex flex-wrap gap-2.5 sm:gap-4 text-[11px] sm:text-xs font-semibold text-zinc-700">
                                <span class="flex items-center space-x-1.5 bg-white/90 px-3.5 py-1.5 rounded-xl border border-amber-900/10 shadow-xs">
                                    <span class="text-amber-700">🌱</span> <span>Madera Sustentable</span>
                                </span>
                                <span class="flex items-center space-x-1.5 bg-white/90 px-3.5 py-1.5 rounded-xl border border-amber-900/10 shadow-xs">
                                    <span class="text-amber-700">📦</span> <span>Envío Gratis &gt; $8,000 MXN</span>
                                </span>
                                <span class="flex items-center space-x-1.5 bg-white/90 px-3.5 py-1.5 rounded-xl border border-amber-900/10 shadow-xs">
                                    <span class="text-amber-700">🛡️</span> <span>Garantía de 3 Años</span>
                                </span>
                            </div>

                            <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                                <a href="{{ route('catalogo') }}" class="inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-amber-800 via-amber-750 to-amber-900 hover:from-amber-700 hover:to-amber-850 text-white text-xs sm:text-sm font-bold px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:shadow-amber-900/20 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <span>Explorar Catálogo</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                                <a href="#hotspots-section" class="inline-flex items-center justify-center bg-white hover:bg-zinc-50 text-zinc-900 text-xs sm:text-sm font-bold px-7 py-4 rounded-2xl border border-zinc-200 shadow-sm transition-all duration-300">
                                    <span>Inspírate en la Sala</span>
                                </a>
                            </div>
                        </div>

                        <!-- Columna Derecha: Visor 3D Cristalino -->
                        <div class="lg:col-span-5 relative">
                            <div class="absolute -inset-2 bg-gradient-to-r from-amber-300/40 to-orange-200/30 rounded-3xl blur-2xl pointer-events-none"></div>

                            <div class="relative bg-white/95 backdrop-blur-xl border border-amber-900/10 p-5 rounded-3xl shadow-2xl overflow-hidden group/model">
                                
                                <div class="flex items-center justify-between border-b border-zinc-100 pb-3 mb-2.5">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-600 animate-pulse"></span>
                                        <span id="model-3d-title" class="text-xs font-extrabold text-zinc-950 uppercase tracking-wider">Sillón Velvet Nordik (3D)</span>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-amber-900 bg-amber-100/90 border border-amber-200/90 px-2.5 py-0.5 rounded-full uppercase tracking-widest">
                                        Gíralo 360° 🎮
                                    </span>
                                </div>

                                <div class="relative w-full h-[300px] sm:h-[360px] rounded-2xl bg-gradient-to-b from-stone-50/90 via-white to-amber-50/40 overflow-hidden flex items-center justify-center border border-stone-200/60 shadow-inner">
                                    <model-viewer
                                        id="furniture-3d-viewer"
                                        src="https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/SheenChair/glTF-Binary/SheenChair.glb"
                                        alt="Sillón de Diseño Escandinavo 3D"
                                        auto-rotate
                                        camera-controls
                                        shadow-intensity="1.5"
                                        shadow-softness="0.9"
                                        exposure="1.2"
                                        ar
                                        camera-orbit="35deg 75deg 2.2m"
                                        field-of-view="32deg"
                                        touch-action="pan-y"
                                        style="width: 100%; height: 100%; background-color: transparent;"
                                        loading="eager">
                                    </model-viewer>

                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur-md px-3.5 py-1 rounded-full text-[10px] font-bold text-amber-900 pointer-events-none border border-amber-200 shadow-md flex items-center space-x-1">
                                        <span>👇 Arrastra para girar el mueble 3D</span>
                                    </div>
                                </div>

                                <div class="mt-3.5 grid grid-cols-3 gap-2">
                                    <button type="button" onclick="switch3DModel('SheenChair', 'Sillón Velvet Nordik', 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/SheenChair/glTF-Binary/SheenChair.glb', this)" class="model-select-btn bg-amber-800 text-white border-amber-700 p-2 rounded-xl text-[11px] font-bold border flex items-center justify-center space-x-1 transition-all shadow-sm">
                                        <span>🛋️ Sillón</span>
                                    </button>
                                    <button type="button" onclick="switch3DModel('Chair', 'Silla Eames Roble', 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/Chair/glTF-Binary/Chair.glb', this)" class="model-select-btn bg-zinc-100 hover:bg-amber-50 text-zinc-700 border-zinc-200/90 p-2 rounded-xl text-[11px] font-bold border flex items-center justify-center space-x-1 transition-all">
                                        <span>🪑 Silla</span>
                                    </button>
                                    <button type="button" onclick="switch3DModel('GlamVelvetSofa', 'Sofá Luxury Velvet', 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/GlamVelvetSofa/glTF-Binary/GlamVelvetSofa.glb', this)" class="model-select-btn bg-zinc-100 hover:bg-amber-50 text-zinc-700 border-zinc-200/90 p-2 rounded-xl text-[11px] font-bold border flex items-center justify-center space-x-1 transition-all">
                                        <span>🛋️ Sofá</span>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="hero-slide absolute inset-0 z-0 opacity-0 transition-opacity duration-1000 ease-in-out flex items-center pointer-events-none">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=1800" alt="Venta Especial de Salas de Autor" class="w-full h-full object-cover opacity-15 mix-blend-multiply scale-105 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#FAF8F5] via-[#FAF8F5]/90 to-transparent"></div>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 lg:py-24 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        
                        <!-- Columna Izquierda -->
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-rose-100/90 border border-rose-200 text-rose-900 text-[10px] sm:text-xs font-extrabold uppercase tracking-widest shadow-xs mb-4 sm:mb-6">
                                <span>🔥 Oferta de Temporada</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                            </div>

                            <h2 class="serif-title text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-zinc-950 leading-[1.10]">
                                Hasta <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-700 via-amber-800 to-rose-900">35% de Descuento</span> en Salas de Autor
                            </h2>

                            <p class="mt-4 sm:mt-6 text-sm sm:text-base lg:text-lg text-zinc-600 font-normal leading-relaxed">
                                Aprovecha precios especiales en sofás modulares, credenzas artesanales y mesas auxiliares confeccionadas con maderas nobles.
                            </p>

                            <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                                <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="inline-flex items-center justify-center space-x-2 bg-rose-700 hover:bg-rose-800 text-white text-xs sm:text-sm font-bold px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:shadow-rose-900/20 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <span>Ver Descuentos de Salón</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Columna Derecha: Visor 3D Sofá Luxury -->
                        <div class="lg:col-span-5 relative pointer-events-auto">
                            <div class="absolute -inset-2 bg-gradient-to-r from-rose-200/50 to-amber-200/30 rounded-3xl blur-2xl pointer-events-none"></div>

                            <div class="relative bg-white/95 backdrop-blur-xl border border-rose-900/10 p-5 rounded-3xl shadow-2xl overflow-hidden">
                                <div class="flex items-center justify-between border-b border-zinc-100 pb-3 mb-2.5">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-ping"></span>
                                        <span class="text-xs font-extrabold text-zinc-950 uppercase tracking-wider">Sofá Luxury Velvet (3D)</span>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-rose-900 bg-rose-100 border border-rose-200 px-2.5 py-0.5 rounded-full uppercase tracking-widest">
                                        35% OFF 🔥
                                    </span>
                                </div>

                                <div class="relative w-full h-[300px] sm:h-[360px] rounded-2xl bg-gradient-to-b from-stone-50/90 via-white to-rose-50/30 overflow-hidden flex items-center justify-center border border-stone-200/60 shadow-inner">
                                    <model-viewer
                                        src="https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/GlamVelvetSofa/glTF-Binary/GlamVelvetSofa.glb"
                                        alt="Sofá Luxury Velvet 3D"
                                        auto-rotate
                                        camera-controls
                                        shadow-intensity="1.8"
                                        exposure="1.2"
                                        camera-orbit="40deg 75deg 2.8m"
                                        field-of-view="35deg"
                                        touch-action="pan-y"
                                        style="width: 100%; height: 100%; background-color: transparent;"
                                        loading="lazy">
                                    </model-viewer>
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur-md px-3.5 py-1 rounded-full text-[10px] font-bold text-rose-900 pointer-events-none border border-rose-200 shadow-md flex items-center space-x-1">
                                        <span>🎮 Arrastra para explorar el sofá 3D</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="hero-slide absolute inset-0 z-0 opacity-0 transition-opacity duration-1000 ease-in-out flex items-center pointer-events-none">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1617806118233-18e1db207f62?q=80&w=1800" alt="Comedores y Proyectos a la Medida" class="w-full h-full object-cover opacity-15 mix-blend-multiply scale-105 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#FAF8F5] via-[#FAF8F5]/90 to-transparent"></div>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 lg:py-24 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        
                        <!-- Columna Izquierda -->
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-amber-100/90 border border-amber-200 text-amber-900 text-[10px] sm:text-xs font-extrabold uppercase tracking-widest shadow-xs mb-4 sm:mb-6">
                                <span>📐 Asesoría de Interiorismo Incluida</span>
                            </div>

                            <h2 class="serif-title text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-zinc-950 leading-[1.10]">
                                Diseño a la medida para <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-800 via-amber-700 to-amber-900">tu espacio ideal</span>
                            </h2>

                            <p class="mt-4 sm:mt-6 text-sm sm:text-base lg:text-lg text-zinc-600 font-normal leading-relaxed">
                                Coordina acabados, medidas y texturas para comedores y dormitorios con el acompañamiento directo de nuestros diseñadores.
                            </p>

                            <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                                <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-amber-800 to-amber-900 hover:from-amber-700 hover:to-amber-850 text-white text-xs sm:text-sm font-bold px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-0.5">
                                    <span>Colección Comedores</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Columna Derecha: Visor 3D Silla Eames Roble -->
                        <div class="lg:col-span-5 relative pointer-events-auto">
                            <div class="absolute -inset-2 bg-gradient-to-r from-amber-200/50 to-orange-200/30 rounded-3xl blur-2xl pointer-events-none"></div>

                            <div class="relative bg-white/95 backdrop-blur-xl border border-amber-900/10 p-5 rounded-3xl shadow-2xl overflow-hidden">
                                <div class="flex items-center justify-between border-b border-zinc-100 pb-3 mb-2.5">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-600 animate-pulse"></span>
                                        <span class="text-xs font-extrabold text-zinc-950 uppercase tracking-wider">Silla Eames Roble (3D)</span>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-amber-900 bg-amber-100 border border-amber-200 px-2.5 py-0.5 rounded-full uppercase tracking-widest">
                                        Madera Maciza 🪵
                                    </span>
                                </div>

                                <div class="relative w-full h-[300px] sm:h-[360px] rounded-2xl bg-gradient-to-b from-stone-50/90 via-white to-amber-50/30 overflow-hidden flex items-center justify-center border border-stone-200/60 shadow-inner">
                                    <model-viewer
                                        src="https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/Chair/glTF-Binary/Chair.glb"
                                        alt="Silla Eames Roble 3D"
                                        auto-rotate
                                        camera-controls
                                        shadow-intensity="1.8"
                                        exposure="1.2"
                                        camera-orbit="30deg 75deg 2.2m"
                                        field-of-view="30deg"
                                        style="width: 100%; height: 100%; background-color: transparent;"
                                        loading="lazy">
                                    </model-viewer>
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur-md px-3.5 py-1 rounded-full text-[10px] font-bold text-amber-900 pointer-events-none border border-amber-200 shadow-md flex items-center space-x-1">
                                        <span>🎮 Arrastra para examinar la silla 3D</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Botones de Navegación Flechas Elegantes -->
        <button id="slider-prev-btn" aria-label="Anterior diapositiva" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/80 hover:bg-white text-zinc-900 flex items-center justify-center border border-zinc-200 shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button id="slider-next-btn" aria-label="Siguiente diapositiva" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/80 hover:bg-white text-zinc-900 flex items-center justify-center border border-zinc-200 shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Indicadores de Carrusel / Progress Dots -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center space-x-2">
            <button class="slider-dot w-8 h-2 rounded-full bg-amber-800 transition-all duration-300" data-slide="0" aria-label="Ir a diapositiva 1"></button>
            <button class="slider-dot w-2.5 h-2.5 rounded-full bg-zinc-300 hover:bg-zinc-400 transition-all duration-300" data-slide="1" aria-label="Ir a diapositiva 2"></button>
            <button class="slider-dot w-2.5 h-2.5 rounded-full bg-zinc-300 hover:bg-zinc-400 transition-all duration-300" data-slide="2" aria-label="Ir a diapositiva 3"></button>
        </div>
    </div>


    <!-- ── 2. BARRA DE BENEFICIOS Y GARANTÍAS (TRUST BADGES CLAROS) ── -->
    <div class="bg-white py-12 sm:py-16 border-b border-zinc-200/80 shadow-xs relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="hover-lift bg-[#FAF8F5] p-6 rounded-2xl border border-zinc-200/80 shadow-xs flex items-start space-x-4 group hover:border-amber-700/40 hover:bg-white transition-all duration-300">
                    <div class="p-3.5 bg-amber-100 text-amber-900 rounded-2xl group-hover:bg-amber-800 group-hover:text-white transition-colors duration-300 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Envío Gratuito Premium</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">En todas las órdenes mayores a $8,000 MXN en la República Mexicana.</p>
                    </div>
                </div>

                <div class="hover-lift bg-[#FAF8F5] p-6 rounded-2xl border border-zinc-200/80 shadow-xs flex items-start space-x-4 group hover:border-amber-700/40 hover:bg-white transition-all duration-300">
                    <div class="p-3.5 bg-amber-100 text-amber-900 rounded-2xl group-hover:bg-amber-800 group-hover:text-white transition-colors duration-300 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Garantía Extendida 3 Años</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">Protección integral contra defectos de fábrica y estructura.</p>
                    </div>
                </div>

                <div class="hover-lift bg-[#FAF8F5] p-6 rounded-2xl border border-zinc-200/80 shadow-xs flex items-start space-x-4 group hover:border-amber-700/40 hover:bg-white transition-all duration-300">
                    <div class="p-3.5 bg-amber-100 text-amber-900 rounded-2xl group-hover:bg-amber-800 group-hover:text-white transition-colors duration-300 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Pago 100% Seguro</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">Encriptación bancaria SSL y meses sin intereses en tarjetas elegibles.</p>
                    </div>
                </div>

                <div class="hover-lift bg-[#FAF8F5] p-6 rounded-2xl border border-zinc-200/80 shadow-xs flex items-start space-x-4 group hover:border-amber-700/40 hover:bg-white transition-all duration-300">
                    <div class="p-3.5 bg-amber-100 text-amber-900 rounded-2xl group-hover:bg-amber-800 group-hover:text-white transition-colors duration-300 shadow-inner flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Montaje Profesional</h3>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">Servicio opcional de desempaque y ensamble directo en tu domicilio.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ── 3. BANNER DE OFERTA FLASH (LUJO CLARO & DORADO) ── -->
    <div class="bg-gradient-to-r from-amber-500/10 via-amber-100/70 to-orange-50/80 py-12 sm:py-16 text-zinc-950 border-y border-amber-200/80 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            
            <div class="text-center lg:text-left max-w-xl">
                <span class="inline-flex items-center space-x-2 text-xs font-extrabold uppercase tracking-widest text-amber-900 bg-white/80 border border-amber-300/80 px-4 py-1.5 rounded-full mb-3 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-600 animate-ping"></span>
                    <span>Venta Especial Flash 2026</span>
                </span>
                <h2 class="serif-title text-3xl sm:text-5xl font-bold leading-tight text-zinc-950">Últimas Horas: Colección Escandinava</h2>
                <p class="mt-2 text-zinc-700 text-sm sm:text-base font-normal">Obtén un <strong class="text-amber-900 font-bold">15% EXTRA</strong> en tu carrito aplicando el código exclusivo de temporada.</p>
                
                <!-- Botón Copiar Cupón -->
                <div class="mt-5 inline-flex items-center space-x-2 bg-white p-1.5 pl-4 rounded-2xl border border-amber-300/90 shadow-md">
                    <span class="text-xs font-mono font-extrabold tracking-widest text-amber-900" id="coupon-code-val">SECTOR2026</span>
                    <button type="button" onclick="copyCouponCode()" id="copy-coupon-btn" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-xs">
                        Copiar Cupón
                    </button>
                </div>
            </div>

            <!-- Ticker de Reloj / Cuenta Regresiva Claro -->
            <div class="flex items-center space-x-3 sm:space-x-4 text-center">
                <div class="bg-white border border-amber-300/80 p-3.5 sm:p-4 rounded-2xl w-18 sm:w-22 shadow-lg">
                    <span id="flash-hours" class="block text-2xl sm:text-4xl font-extrabold text-amber-900 font-mono">08</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-zinc-500">Horas</span>
                </div>
                <span class="text-2xl sm:text-4xl font-bold text-amber-800 animate-pulse">:</span>
                <div class="bg-white border border-amber-300/80 p-3.5 sm:p-4 rounded-2xl w-18 sm:w-22 shadow-lg">
                    <span id="flash-minutes" class="block text-2xl sm:text-4xl font-extrabold text-amber-900 font-mono">42</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-zinc-500">Minutos</span>
                </div>
                <span class="text-2xl sm:text-4xl font-bold text-amber-800 animate-pulse">:</span>
                <div class="bg-white border border-amber-300/80 p-3.5 sm:p-4 rounded-2xl w-18 sm:w-22 shadow-lg">
                    <span id="flash-seconds" class="block text-2xl sm:text-4xl font-extrabold text-amber-900 font-mono">19</span>
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
                    <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=700" alt="Salón" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col justify-end">
                        <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest">Estancia Salón</span>
                        <h3 class="text-2xl font-bold mt-1 group-hover:text-amber-200 transition-colors">Salón</h3>
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1.5 transition-transform">
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
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1.5 transition-transform">
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
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1.5 transition-transform">
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
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1.5 transition-transform">
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
                        <span class="mt-2 inline-flex items-center text-xs font-semibold text-zinc-200 group-hover:translate-x-1.5 transition-transform">
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
                            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                            
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
                                    action="{{ route('carrito.agregar', $producto->id) }}"
                                    method="POST"
                                    data-nombre="{{ $producto->nombre }}"
                                    data-img="{{ $producto->imagen_url }}"
                                    onsubmit="return window.SM && window.SM.agregarCarrito(event, this)">
                                    @csrf
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


    <!-- ── 7. SECCIÓN DE PRUEBA SOCIAL / RESEÑAS DE CLIENTES ── -->
    <div class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center space-x-1.5 text-amber-500 text-sm mb-2">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    <span class="text-zinc-800 font-bold text-xs ml-2">4.9 / 5.0 en +1,200 Reseñas</span>
                </div>
                <h2 class="serif-title text-3xl sm:text-5xl font-bold text-zinc-950">Experiencias de Nuestros Clientes</h2>
                <p class="mt-2 text-zinc-600 text-sm sm:text-base font-light">Descubre por qué arquitectos y amantes del diseño confían en Sector Mueble para vestir sus espacios.</p>
            </div>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="bg-white p-8 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:shadow-lg transition-all">
                    <div>
                        <div class="flex text-amber-500 text-xs mb-4">★★★★★</div>
                        <p class="text-zinc-700 text-sm italic leading-relaxed">
                            "La calidad de la madera maciza superó mis expectativas. El armado en casa fue impecable y el sofá modular luce espectacular en nuestro proyecto residencial."
                        </p>
                    </div>
                    <div class="mt-6 flex items-center space-x-3 pt-4 border-t border-zinc-100">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=120" alt="Cliente Valery" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="text-xs font-bold text-zinc-950">Arq. Valery Mendoza</h4>
                            <span class="text-[11px] text-zinc-400">Cliente Verificado • Ciudad de México</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:shadow-lg transition-all">
                    <div>
                        <div class="flex text-amber-500 text-xs mb-4">★★★★★</div>
                        <p class="text-zinc-700 text-sm italic leading-relaxed">
                            "Compré la mesa de comedor de roble. El empaque protector multicapa llegó perfecto y el seguimiento de envío por WhatsApp fue constante de principio a fin."
                        </p>
                    </div>
                    <div class="mt-6 flex items-center space-x-3 pt-4 border-t border-zinc-100">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=120" alt="Cliente Rodrigo" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="text-xs font-bold text-zinc-950">Rodrigo Alarcón</h4>
                            <span class="text-[11px] text-zinc-400">Cliente Verificado • Guadalajara</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:shadow-lg transition-all">
                    <div>
                        <div class="flex text-amber-500 text-xs mb-4">★★★★★</div>
                        <p class="text-zinc-700 text-sm italic leading-relaxed">
                            "El diseño minimalista nórdico de Sector Mueble le dio a mi estudio de arquitectura exactamente el tono de elegancia cálida que estaba buscando."
                        </p>
                    </div>
                    <div class="mt-6 flex items-center space-x-3 pt-4 border-t border-zinc-100">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=120" alt="Cliente Diana" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="text-xs font-bold text-zinc-950">Interiorista Diana Campos</h4>
                            <span class="text-[11px] text-zinc-400">Cliente Verificado • Monterrey</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ── 8. BANNER DE PRIVILEGIOS / NEWSLETTER ── -->
    <div class="bg-zinc-950 text-white py-20 sm:py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-gradient-to-r from-zinc-900 via-zinc-900 to-amber-950 p-8 sm:p-14 rounded-3xl border border-zinc-800 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-xl text-center lg:text-left">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-amber-400 bg-amber-400/10 border border-amber-400/20 px-3.5 py-1.5 rounded-full">Club de Miembros VIP</span>
                    <h2 class="serif-title text-3xl sm:text-4xl font-bold mt-3">Recibe $500 MXN en tu Primera Compra</h2>
                    <p class="mt-2 text-zinc-300 text-sm sm:text-base font-light">Suscríbete para recibir lanzamientos privados, catálogos digitales exclusivos y promociones de temporada antes que nadie.</p>
                </div>

                <form onsubmit="handleNewsletterSubmit(event)" class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                    <input type="email" id="newsletter-email" required placeholder="Tu correo electrónico..." class="px-5 py-3.5 rounded-2xl bg-zinc-950 border border-zinc-700 text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-amber-500 w-full sm:w-80">
                    <button type="submit" class="bg-amber-700 hover:bg-amber-600 text-white text-xs font-bold uppercase tracking-wider px-7 py-3.5 rounded-2xl transition-all shadow-xl whitespace-nowrap">
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
