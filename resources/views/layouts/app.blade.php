<!DOCTYPE html>
<html lang="es" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Sector Mueble | E-commerce de Muebles de Diseño')</title>
    <meta name="description" content="Encuentra los mejores muebles de diseño escandinavo, industrial y moderno para tu hogar u oficina en Sector Mueble. Envíos a todo el país.">
    
    <!-- Google Fonts CDN para Máxima Legibilidad en Todos los Dispositivos Móviles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Google Model Viewer para renders 3D interactivos -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>

    <!-- Styles and Scripts via Vite con Fallback Garantizado para Producción -->
    @php
        $cssBuildFile = null;
        $jsBuildFile = null;
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(@file_get_contents($manifestPath), true);
            $cssBuildFile = $manifest['resources/css/app.css']['file'] ?? null;
            $jsBuildFile = $manifest['resources/js/app.js']['file'] ?? null;
        }
    @endphp

    @if($cssBuildFile && !file_exists(public_path('hot')))
        <link rel="stylesheet" href="{{ asset('build/' . $cssBuildFile) }}">
        @if($jsBuildFile)
            <script type="module" src="{{ asset('build/' . $jsBuildFile) }}"></script>
        @endif
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Tailwind CDN Fallback para seguridad total de estilos en el servidor de producción -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Reglas defensivas globales para evitar desbordamiento de imágenes o SVGs */
        img, svg {
            max-width: 100%;
            height: auto;
        }
        img.brand-logo-img {
            max-height: 48px !important;
            width: auto !important;
            object-fit: contain !important;
        }

        body {
            font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #0B0A0A;
        }
        .serif-title, h1, h2, h3, .font-heading {
            font-family: 'NT Fabulous', 'Playfair Display', 'Poppins', Georgia, serif;
        }
        .font-subtitle, .subtitle-brand {
            font-family: 'NT Fabulous Alternative', 'Playfair Display', 'Poppins', Georgia, serif;
        }

        /* ── Animación vuelo al carrito ── */
        @keyframes smCartBounce {
            0%,100% { transform: scale(1) rotate(0deg); }
            20%      { transform: scale(1.35) rotate(-12deg); }
            45%      { transform: scale(1.2)  rotate(8deg); }
            70%      { transform: scale(1.28) rotate(-6deg); }
        }
        .sm-cart-bounce { animation: smCartBounce .55s ease-in-out; }

        @keyframes smBadgePop {
            0%,100% { transform: translate(33%,-33%) scale(1); }
            40%      { transform: translate(33%,-33%) scale(1.6); }
        }
        .sm-badge-pop { animation: smBadgePop .4s cubic-bezier(.36,.07,.19,.97) both; }

        /* Modal */
        #sm-cart-modal .sm-modal-card {
            transition: opacity .28s ease, transform .28s cubic-bezier(.34,1.56,.64,1);
        }
        #sm-cart-modal .sm-modal-overlay {
            transition: opacity .25s ease;
        }

        /* Checkmark draw */
        .sm-check-path {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            transition: stroke-dashoffset .5s ease .2s;
        }
        .sm-check-path.drawn { stroke-dashoffset: 0; }
    </style>
</head>
<body class="flex flex-col min-h-screen text-zinc-800">

@include('Principal.partials.modal-cp')

<!-- Banner Superior de Leyenda Informativa / Precios de Muestra (Demostración) -->
<div class="bg-amber-950 text-amber-100 text-xs py-2 px-4 border-b border-amber-800/40 text-center font-medium shadow-sm z-[60] relative">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
        <span class="inline-flex items-center space-x-1 bg-amber-800/80 text-amber-200 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded tracking-wider">
            <svg class="w-3 h-3 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Sitio de Demostración</span>
        </span>
        <span class="text-amber-100/90 text-[11px] sm:text-xs">
            Los precios, productos y promociones mostrados en este sitio web son <strong>exclusivamente de muestra</strong> (fines educativos/demo) y no representan ofertas comerciales ni ventas reales.
        </span>
    </div>
</div>

@auth
    @if(!auth()->user()->hasVerifiedEmail() && !request()->routeIs('verification.notice'))
        <div class="bg-amber-600 text-white text-xs py-2 px-4 text-center font-medium shadow-sm z-[55] relative flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-amber-200 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Tu dirección de correo electrónico aún no está verificada.</span>
            <a href="{{ route('verification.notice') }}" class="underline font-bold hover:text-amber-100 transition-colors">
                Verificar correo ahora
            </a>
        </div>
    @endif
@endauth

@php
    $ruletaOpcionesData = \App\Models\RuletaOpcion::where('activo', true)->orderBy('posicion', 'asc')->get();
    $cuponSesion = session()->get('cupon');
    $haJugadoRuleta = (auth()->check() && auth()->user()->ruleta_jugada) || session('ruleta_jugada') || session()->has('cupon');
@endphp

<!-- Banner Sticky de Premio Activo de Ruleta -->
<div id="ruleta-sticky-banner" class="{{ ($cuponSesion && isset($cuponSesion['expira_en'])) ? '' : 'hidden' }} sticky top-0 z-50 bg-gradient-to-r from-amber-900 via-amber-800 to-amber-950 text-white px-4 py-2.5 shadow-md border-b border-amber-600">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm">
        <div class="flex items-center space-x-2 font-medium">
            <span class="animate-bounce text-base">🎁</span>
            <span><strong>¡Premio de Ruleta Activo!</strong> <span id="ruleta-banner-titulo">{{ $cuponSesion['titulo'] ?? ($cuponSesion['codigo'] ?? 'Descuento Especial') }}</span></span>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-black/40 px-3 py-1 rounded-full border border-amber-400/40 flex items-center space-x-1.5 font-mono text-amber-200">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Expira en:</span>
                <strong id="ruleta-banner-timer" class="text-white font-bold text-sm">--:--</strong>
            </div>
            <a href="{{ route('carrito') }}" class="bg-amber-500 hover:bg-amber-400 text-amber-950 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider transition-colors shadow">
                Ir al Carrito
            </a>
        </div>
    </div>
</div>

<!-- Banner Sticky de Notificación de Productos Esperando en Carrito -->
@if(session()->has('notificacion_carrito_abandonado') || (auth()->check() && !empty(auth()->user()->carrito_guardado) && session()->has('carrito') && count(session('carrito', [])) > 0 && !request()->routeIs('carrito') && !request()->routeIs('checkout')))
<div id="carrito-guardado-banner" class="sticky top-0 z-[48] bg-gradient-to-r from-amber-950 via-amber-900 to-amber-950 text-white px-4 py-2.5 shadow-md border-b border-amber-600/60">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm">
        <div class="flex items-center space-x-2 font-medium">
            <span class="animate-bounce text-base">🛒</span>
            <span><strong>¡Tus productos te están esperando!</strong> Dejamos guardados los muebles que tenías en tu carrito para que puedas completar tu compra.</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('carrito') }}" class="bg-amber-400 hover:bg-amber-300 text-amber-950 text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider transition-colors shadow">
                Ver mi Carrito ({{ array_sum(array_column(session('carrito', []), 'cantidad')) }})
            </a>
            <button type="button" onclick="document.getElementById('carrito-guardado-banner').remove()" class="text-amber-300 hover:text-white text-xs font-bold px-1.5 py-0.5" title="Cerrar aviso">
                ✕
            </button>
        </div>
    </div>
</div>
@endif

    <!-- Header / Navbar con 2 Filas -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-amber-900/10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- FILA 1: Logos (Izquierda), Buscador (Centro), Acciones (Derecha: Carrito, Login, Registro) -->
            <div class="flex items-center justify-between h-16 sm:h-20 py-2 border-b border-zinc-150">
                
                <!-- Botón Menú Móvil (Sólo en pantallas pequeñas) -->
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2.5 rounded-xl text-zinc-700 hover:text-amber-800 hover:bg-amber-50 focus:outline-none transition-colors" aria-label="Abrir menú">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Logos Principales (Izquierda) -->
                <div class="flex-shrink flex items-center space-x-1.5 sm:space-x-2.5 min-w-0">
                    <a href="{{ route('inicio') }}" class="flex items-center space-x-1.5 sm:space-x-2.5 group py-1">
                        <div class="relative flex items-center justify-center p-1 bg-gradient-to-br from-amber-500/10 to-amber-800/10 rounded-xl border border-amber-800/15 shadow-sm shrink-0">
                            <img src="{{ asset('logo2.png') }}" alt="Sector Mueble Isotipo" class="h-6 sm:h-9 md:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-sm brand-logo-img" style="max-height: 40px; max-width: 120px;">
                        </div>
                        <img src="{{ asset('logo1.png') }}" alt="Sector Mueble Logotipo" class="h-7 sm:h-10 md:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-sm brand-logo-img" style="max-height: 48px; max-width: 220px;">
                    </a>
                </div>

                <!-- Buscador Rápido en Centro (Desktop) -->
                <form action="{{ route('catalogo') }}" method="GET" class="hidden md:block relative max-w-xs xl:max-w-md w-full mx-4">
                    <input type="text" name="buscar" placeholder="Buscar muebles de diseño..." class="w-full bg-zinc-50 focus:bg-white text-xs px-4 py-2 pr-9 rounded-full border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/50 focus:border-amber-700 transition-all duration-300 shadow-inner">
                    <button type="submit" class="absolute right-3 top-2 text-zinc-400 hover:text-amber-800 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>

                <!-- Bloque Derecha: Acciones de usuario (Carrito, Iniciar Sesión, Registro y CP debajo) -->
                <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0 z-20">
                    
                    <!-- Botón Carrito de Compras -->
                    <a href="{{ route('carrito') }}" id="nav-cart-icon" class="relative flex items-center space-x-1 px-2.5 sm:px-3 py-2 bg-amber-800 hover:bg-amber-700 text-white rounded-xl shadow transition-all duration-300 active:scale-95 flex-shrink-0">
                        <svg class="h-4.5 w-4.5 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="text-xs font-bold whitespace-nowrap hidden sm:inline">Carrito</span>
                        @php
                            $cantidadCarrito = array_sum(array_column(session('carrito', []), 'cantidad'));
                        @endphp
                        <span id="cart-badge" class="{{ $cantidadCarrito > 0 ? '' : 'hidden' }} ml-1 px-1.5 py-0.5 text-[10px] sm:text-[11px] font-extrabold leading-none text-amber-950 bg-amber-300 rounded-full shadow">
                            {{ $cantidadCarrito }}
                        </span>
                    </a>

                    <!-- Usuario Autenticado / Sesión -->
                    @auth
                        <div class="flex items-center space-x-2 border-l border-zinc-200 pl-2 sm:pl-3">
                            <span class="text-xs font-medium text-zinc-700 hidden sm:inline">Hola, <strong class="text-amber-800">{{ auth()->user()->name }}</strong></span>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-[10px] font-bold text-amber-900 bg-amber-100 hover:bg-amber-200 px-2 py-1 rounded-lg uppercase tracking-wider">Admin</a>
                            @endif
                            <a href="{{ route('logout') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 px-2 py-1 rounded-lg hover:bg-rose-50 transition-colors uppercase tracking-wider">Salir</a>
                        </div>
                    @else
                        <div class="flex items-center space-x-1.5 sm:space-x-2 border-l border-zinc-200 pl-2 sm:pl-3">
                            <!-- Botón Iniciar Sesión -->
                            <a href="{{ route('login') }}" class="flex items-center space-x-1 text-xs font-bold text-amber-950 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 px-2.5 sm:px-3.5 py-1.5 rounded-xl transition-all shadow-xs whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="hidden sm:inline">Iniciar Sesión</span>
                                <span class="sm:hidden">Entrar</span>
                            </a>

                            <!-- Botón Registro -->
                            <a href="{{ route('registro') }}" class="hidden sm:inline-flex items-center justify-center text-xs font-bold text-white bg-amber-800 hover:bg-amber-700 px-3.5 py-1.5 rounded-xl transition-all shadow-xs hover:shadow whitespace-nowrap">
                                <span>Registro</span>
                            </a>
                        </div>
                    @endauth

                    <!-- Botón CP Compacto para pantallas móviles pequeños (Celular) -->
                    <button type="button" onclick="abrirModalCP()" class="md:hidden flex items-center space-x-1 text-[11px] font-bold text-amber-900 bg-amber-100/80 border border-amber-300/80 px-2 py-1 rounded-lg shadow-2xs">
                        <svg class="w-3 h-3 text-amber-800 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <span class="cp-header-text-span font-bold text-amber-950">
                            @if(session('codigo_postal'))
                                {{ session('codigo_postal') }}
                            @else
                                CP
                            @endif
                        </span>
                    </button>
                </div>
            </div>

            <!-- FILA 2: Barra de Navegación por Categorías + CP (A la misma altura que las categorías y debajo de Iniciar Sesión) -->
            <div class="hidden md:flex items-center justify-between border-t border-zinc-100/80 py-2 mt-1">
                
                <!-- Categorías (Izquierda) -->
                <nav class="flex items-center space-x-8">
                    <!-- SALA con Submenú Desplegable Estilo Marca -->
                    <div class="relative group">
                        <button class="flex items-center space-x-1.5 text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-amber-800 py-1 transition-colors cursor-pointer">
                            <span>Sala</span>
                            <svg class="w-3.5 h-3.5 text-zinc-500 group-hover:text-amber-800 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Submenú flotante compacto -->
                        <div class="absolute top-full left-0 mt-1.5 w-64 bg-[#2B241A] text-[#FAF3E0] rounded-2xl shadow-2xl border border-[#88674B]/40 p-3 space-y-1 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform group-hover:translate-y-0 translate-y-2">
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Sofás y salas modulares</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Mesas de centro y laterales</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Sillones</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Credenzas</span>
                            </a>
                        </div>
                    </div>

                    <!-- RECÁMARA con Submenú Desplegable Estilo Marca -->
                    <div class="relative group">
                        <button class="flex items-center space-x-1.5 text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-amber-800 py-1 transition-colors cursor-pointer">
                            <span>Recámara</span>
                            <svg class="w-3.5 h-3.5 text-zinc-500 group-hover:text-amber-800 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute top-full left-0 mt-1.5 w-60 bg-[#2B241A] text-[#FAF3E0] rounded-2xl shadow-2xl border border-[#88674B]/40 p-3 space-y-1 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform group-hover:translate-y-0 translate-y-2">
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Camas</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Burós</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Divanes</span>
                            </a>
                        </div>
                    </div>

                    <!-- COMEDOR con Submenú Desplegable Estilo Marca -->
                    <div class="relative group">
                        <button class="flex items-center space-x-1.5 text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-amber-800 py-1 transition-colors cursor-pointer">
                            <span>Comedor</span>
                            <svg class="w-3.5 h-3.5 text-zinc-500 group-hover:text-amber-800 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute top-full left-0 mt-1.5 w-56 bg-[#2B241A] text-[#FAF3E0] rounded-2xl shadow-2xl border border-[#88674B]/40 p-3 space-y-1 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform group-hover:translate-y-0 translate-y-2">
                            <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Sillas</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-amber-200 transition-colors">
                                <span class="text-amber-400 font-bold">•</span>
                                <span>Mesas</span>
                            </a>
                        </div>
                    </div>

                    <!-- CATÁLOGO COMPLETO -->
                    <a href="{{ route('catalogo') }}" class="text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-amber-800 py-1 transition-colors">
                        Catálogo Completo
                    </a>
                </nav>

                <!-- Botón de Código Postal (CP) a la misma altura que Categorías y justo DEBAJO de Iniciar Sesión -->
                <button type="button" onclick="abrirModalCP()" class="inline-flex items-center space-x-1.5 text-xs font-bold text-amber-950 bg-amber-50 hover:bg-amber-100 border border-amber-200/90 px-3 py-1 rounded-xl transition-all shadow-2xs group cursor-pointer" title="Consultar o cambiar tu Código Postal">
                    <svg class="w-3.5 h-3.5 text-amber-800 shrink-0 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="cp-header-text-span font-bold text-amber-950">
                        @if(session('codigo_postal'))
                            CP: <strong>{{ session('codigo_postal') }}</strong>
                        @else
                            Ingresa tu CP
                        @endif
                    </span>
                    <span class="text-[10px] text-amber-700 underline font-normal">(Cambiar)</span>
                </button>

            </div>
        </div>

        <!-- Menú Móvil Desplegable -->
        <div id="mobile-menu-drawer" class="hidden bg-white border-b border-zinc-200 px-4 pt-3 pb-6 space-y-4">
            <form action="{{ route('catalogo') }}" method="GET" class="relative">
                <input type="text" name="buscar" placeholder="Buscar muebles..." class="w-full bg-zinc-50 text-xs px-4 py-2.5 pr-9 rounded-xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700">
                <button type="submit" class="absolute right-3 top-3 text-zinc-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            <div class="grid grid-cols-2 gap-2 text-center text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="p-3 bg-amber-50/60 rounded-xl text-amber-900 hover:bg-amber-100">Salón / Sala</a>
                <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="p-3 bg-amber-50/60 rounded-xl text-amber-900 hover:bg-amber-100">Recámara</a>
                <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="p-3 bg-amber-50/60 rounded-xl text-amber-900 hover:bg-amber-100">Comedor</a>
                <a href="{{ route('catalogo') }}" class="p-3 bg-amber-800 rounded-xl text-white">Todo el Catálogo</a>
            </div>

            <!-- Botón CP Móvil -->
            <button type="button" onclick="toggleMobileMenu(); abrirModalCP();" class="w-full flex items-center justify-center space-x-2 py-3 px-4 bg-amber-100/70 text-amber-950 rounded-xl border border-amber-300/80 font-bold text-xs">
                <svg class="w-4 h-4 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="cp-header-text-span">Consultar / Cambiar Código Postal</span>
            </button>

            @auth
                <div class="pt-2 border-t border-zinc-100 flex items-center justify-between text-xs">
                    <span class="font-medium text-zinc-700">Hola, <strong>{{ auth()->user()->name }}</strong></span>
                    <a href="{{ route('logout') }}" class="text-rose-600 font-bold">Cerrar Sesión</a>
                </div>
            @else
                <div class="pt-2 border-t border-zinc-100 flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="w-1/2 text-center py-2.5 bg-zinc-900 text-white rounded-xl font-bold text-xs">Iniciar Sesión</a>
                    <a href="{{ route('registro') }}" class="w-1/2 text-center py-2.5 bg-amber-800 text-white rounded-xl font-bold text-xs">Registro</a>
                </div>
            @endauth
        </div>

    </header>

    <script>
    function toggleMobileMenu() {
        const drawer = document.getElementById('mobile-menu-drawer');
        if (drawer) {
            drawer.classList.toggle('hidden');
        }
    }

    function switchSubnav(category) {
        const megaPanel = document.getElementById('mega-subnav-panel');
        if (megaPanel) {
            megaPanel.classList.remove('hidden');
        }

        // Ocultar todos los paneles para mostrar unicamente el seleccionado
        document.querySelectorAll('.subnav-content-panel').forEach(function(panel) {
            panel.classList.add('hidden');
        });
        
        // Desactivar todos los estilos activos de las pestañas
        document.querySelectorAll('.subnav-tab').forEach(function(tab) {
            tab.classList.remove('text-red-700', 'border-red-600', 'font-bold');
            tab.classList.add('text-zinc-700', 'border-transparent', 'font-semibold');
        });

        // Activar la pestaña y el panel correspondiente
        const activeTab = document.getElementById('tab-' + category);
        const activePanel = document.getElementById('panel-' + category);

        if (activeTab) {
            activeTab.classList.add('text-red-700', 'border-red-600', 'font-bold');
            activeTab.classList.remove('text-zinc-700', 'border-transparent', 'font-semibold');
        }

        if (activePanel) {
            activePanel.classList.remove('hidden');
        }
    }

    function closeSubnav() {
        const megaPanel = document.getElementById('mega-subnav-panel');
        if (megaPanel) {
            megaPanel.classList.add('hidden');
        }
        document.querySelectorAll('.subnav-tab').forEach(function(tab) {
            tab.classList.remove('text-red-700', 'border-red-600', 'font-bold');
            tab.classList.add('text-zinc-700', 'border-transparent', 'font-semibold');
        });
    }
    </script>

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center justify-between p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded text-emerald-800">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center justify-between p-4 bg-rose-50 border-l-4 border-rose-500 rounded text-rose-800">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow pb-20 md:pb-0">
        @yield('contenido')
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-900 text-zinc-400 border-t border-zinc-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Logo & Desc -->
                <div>
                    <a href="{{ route('inicio') }}" class="flex items-center space-x-2.5 group">
                        <div class="p-1 bg-white/10 rounded-lg border border-white/10">
                            <img src="{{ asset('logo2.png') }}" alt="Isotipo Sector Mueble" class="h-7 sm:h-8 w-auto object-contain brightness-0 invert opacity-95 group-hover:scale-105 transition-all">
                        </div>
                        <img src="{{ asset('logo1.png') }}" alt="Sector Mueble" class="h-8 sm:h-10 w-auto object-contain brightness-0 invert opacity-95 group-hover:scale-105 transition-all">
                    </a>
                    <p class="mt-4 text-sm text-zinc-400 leading-relaxed">
                        Creamos espacios de vida inspiradores y confortables. Nuestra selección curada de muebles escandinavos y modernos fusiona estética y durabilidad a precios justos.
                    </p>
                </div>

                <!-- Col 2: Categories -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Categorías</h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="hover:text-amber-500 transition-colors">Salón y Estancia</a></li>
                        <li><a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="hover:text-amber-500 transition-colors">Dormitorio y Cunas</a></li>
                        <li><a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="hover:text-amber-500 transition-colors">Comedor y Cocina</a></li>
                        <li><a href="{{ route('catalogo', ['categoria' => 'Oficina']) }}" class="hover:text-amber-500 transition-colors">Oficina y Despacho</a></li>
                        <li><a href="{{ route('catalogo', ['categoria' => 'Exterior']) }}" class="hover:text-amber-500 transition-colors">Jardín y Exterior</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Care -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Servicio al Cliente</h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><a href="#" class="hover:text-amber-500 transition-colors">Preguntas Frecuentes</a></li>
                        <li><a href="#" class="hover:text-amber-500 transition-colors">Políticas de Envío</a></li>
                        <li><a href="#" class="hover:text-amber-500 transition-colors">Garantía de Satisfacción</a></li>
                        <li><a href="#" class="hover:text-amber-500 transition-colors">Términos y Condiciones</a></li>
                    </ul>
                </div>

                <!-- Col 4: Newsletter -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Suscríbete</h3>
                    <p class="mt-4 text-sm text-zinc-400">Recibe 10% de descuento en tu primera compra y novedades exclusivas.</p>
                    <form action="#" class="mt-4 flex max-w-md">
                        <input type="email" placeholder="Tu correo electrónico" required class="w-full bg-zinc-800 text-white text-xs px-4 py-3 rounded-l border border-zinc-700 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-transparent">
                        <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white text-xs px-6 py-3 rounded-r font-medium transition-colors">Unirse</button>
                    </form>
                </div>
            </div>

            <!-- Cuadro de Descargo Legal / Precios de Muestra -->
            <div class="mt-10 p-4.5 rounded-2xl bg-zinc-950/80 border border-zinc-800/90 text-xs text-zinc-400 leading-relaxed">
                <div class="flex items-center space-x-2 font-bold text-amber-400 mb-1.5">
                    <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="uppercase tracking-wider text-[11px]">Aviso de Demostración y Descargo Legal</span>
                </div>
                <p class="text-zinc-400">
                    Este sitio web es una plataforma de prueba desarrollada exclusivamente con fines de demostración, portafolio y exhibición técnica. 
                    Todos los nombres de productos, fotografías, descripciones, precios, descuentos y procesos de compra son ficticios y de muestra. 
                    No existe relación mercantil real, no se cobran importes ni se despacha mercancía alguna a través de este portal.
                </p>
            </div>

            <!-- Bottom Area -->
            <div class="mt-8 pt-8 border-t border-zinc-800 flex flex-col md:flex-row items-center justify-between text-xs">
                <p>&copy; {{ date('Y') }} Sector Mueble S.L. Todos los derechos reservados. Creado con pasión por el diseño.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <span class="hover:text-white transition-colors cursor-pointer">Instagram</span>
                    <span class="hover:text-white transition-colors cursor-pointer">Pinterest</span>
                    <span class="hover:text-white transition-colors cursor-pointer">Facebook</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Barra de Navegación Flotante Inferior para Celulares (Mobile App-Like Experience) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-[80] bg-white/95 backdrop-blur-lg border-t border-zinc-200/90 shadow-2xl py-2 px-3 flex items-center justify-around">
        <!-- Inicio -->
        <a href="{{ route('inicio') }}" class="flex flex-col items-center space-y-0.5 text-xs font-semibold {{ Route::is('inicio') ? 'text-amber-800 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px]">Inicio</span>
        </a>

        <!-- Catálogo -->
        <a href="{{ route('catalogo') }}" class="flex flex-col items-center space-y-0.5 text-xs font-semibold {{ Route::is('catalogo') ? 'text-amber-800 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
            </svg>
            <span class="text-[10px]">Catálogo</span>
        </a>

        <!-- Carrito -->
        <a href="{{ route('carrito') }}" class="relative flex flex-col items-center space-y-0.5 text-xs font-semibold {{ Route::is('carrito') ? 'text-amber-800 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span id="mobile-cart-badge" class="{{ $cantidadCarrito > 0 ? '' : 'hidden' }} absolute -top-1.5 -right-2.5 bg-amber-600 text-white text-[9px] font-black rounded-full h-4 w-4 flex items-center justify-center shadow">
                    {{ $cantidadCarrito }}
                </span>
            </div>
            <span class="text-[10px]">Carrito</span>
        </a>

        <!-- Usuario / Perfil -->
        @auth
            <a href="{{ route('logout') }}" class="flex flex-col items-center space-y-0.5 text-xs font-semibold text-rose-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="text-[10px]">Salir</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center space-y-0.5 text-xs font-semibold {{ Route::is('login') ? 'text-amber-800 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px]">Ingresar</span>
            </a>
        @endauth
    </nav>

</body>

{{-- ═══════════════════════════════════════════════════════
     MODAL CARRITO — aparece al añadir un artículo
═══════════════════════════════════════════════════════ --}}
<div id="sm-cart-modal" class="hidden fixed inset-0 z-[9999] flex items-end sm:items-center justify-center px-4 pb-6 sm:pb-0" role="dialog" aria-modal="true">

    {{-- Overlay --}}
    <div id="sm-modal-overlay"
         class="sm-modal-overlay absolute inset-0 bg-zinc-900/55 backdrop-blur-sm"
         style="opacity:0"
         onclick="SM.cerrarModal()"></div>

    {{-- Card --}}
    <div id="sm-modal-card"
         class="sm-modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden"
         style="opacity:0; transform:scale(.94) translateY(24px)">

        {{-- Barra superior degradada --}}
        <div class="h-1.5 bg-gradient-to-r from-amber-700 via-amber-500 to-amber-400"></div>

        <div class="p-7">

            {{-- Imagen del producto + check --}}
            <div class="flex justify-center mb-5">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white shadow-xl ring-2 ring-zinc-100">
                        <img id="sm-modal-img" src="" alt="" class="w-full h-full object-cover">
                        <div id="sm-modal-emoji" class="hidden w-full h-full bg-amber-800 flex items-center justify-center text-3xl">🛋️</div>
                    </div>
                    {{-- Badge de check --}}
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path class="sm-check-path" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Texto --}}
            <h3 class="text-center font-bold text-zinc-900 text-xl">¡Añadido al carrito!</h3>
            <p id="sm-modal-nombre" class="text-center text-zinc-500 text-sm mt-1 truncate px-4"></p>

            {{-- Botones de acción --}}
            <div class="mt-7 space-y-3">
                <a href="{{ route('carrito') }}"
                   id="sm-btn-ver-carrito"
                   class="flex items-center justify-center space-x-2 w-full bg-amber-800 hover:bg-amber-700 text-white font-bold text-sm py-3.5 rounded-2xl transition-all duration-300 shadow hover:shadow-md active:scale-[.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Ver Carrito</span>
                </a>

                <button type="button"
                        onclick="SM.cerrarModal()"
                        class="flex items-center justify-center space-x-2 w-full border border-zinc-200 hover:bg-zinc-50 text-zinc-700 font-semibold text-sm py-3.5 rounded-2xl transition-all duration-300 active:scale-[.98]">
                    <span>Seguir Comprando</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

@if(!$haJugadoRuleta)
<!-- Botón Flotante para abrir la Ruleta con Paleta Oficial -->
<button id="ruleta-trigger-btn" onclick="openRuletaModal()" class="fixed bottom-20 md:bottom-6 left-4 sm:left-6 z-40 bg-gradient-to-r from-[#88674B] via-[#74563C] to-[#2B241A] text-white p-3 sm:p-3.5 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 flex items-center space-x-2 border.2 border-[#FAF3E0]/40 group">
    <span class="text-xl animate-bounce">🎡</span>
    <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline-block pr-1 text-[#FAF3E0]">Ruleta de Bienvenida</span>
</button>

<!-- Modal de Ruleta de Premios para Nuevos Usuarios -->
<div id="ruleta-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" style="opacity: 0; transition: opacity 0.3s ease;">
    <!-- Backdrop Overlay -->
    <div class="absolute inset-0 bg-zinc-950/80 backdrop-blur-md" onclick="closeRuletaModal()"></div>

    <!-- Container Card -->
    <div class="relative bg-[#1F0F0B] border-2 border-[#88674B]/60 rounded-3xl shadow-2xl max-w-md w-full p-6 text-white text-center overflow-hidden transform scale-95 transition-transform duration-300" id="ruleta-modal-card">
        <!-- Glow accents -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-[#88674B]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-[#2B241A]/40 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Botón cerrar -->
        <button onclick="closeRuletaModal()" class="absolute top-4 right-4 text-zinc-400 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors z-10">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header Modal -->
        <div id="ruleta-step-spin">
            <div class="mb-4">
                <span class="inline-block bg-[#88674B]/30 border border-[#88674B]/50 text-[#FAF3E0] text-[11px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest mb-2">
                    ✨ Exclusivo Nuevos Clientes ✨
                </span>
                <h3 class="serif-title text-2xl sm:text-3xl font-bold text-white tracking-wide">
                    ¡Gira la Ruleta de Sector Mueble!
                </h3>
                <p class="text-xs text-zinc-300 mt-1">
                    Obtén un cupón exclusivo para tu primera compra de muebles de diseño.
                </p>
            </div>

            <!-- Canvas contenedor de la rueda -->
            <div class="relative mx-auto my-3 w-[270px] h-[270px] sm:w-[300px] sm:h-[300px] flex items-center justify-center">
                <!-- Flecha Indicadora Superior -->
                <div class="absolute -top-3 z-30 flex flex-col items-center">
                    <div class="w-0 h-0 border-l-[14px] border-l-transparent border-r-[14px] border-r-transparent border-t-[22px] border-t-amber-400 drop-shadow-[0_4px_8px_rgba(245,158,11,0.6)]"></div>
                </div>

                <!-- Rueda Canvas -->
                <div id="ruleta-wheel-wrapper" class="w-full h-full rounded-full shadow-[0_0_35px_rgba(136,103,75,0.45)] border-4 border-[#88674B] overflow-hidden relative" style="transition: transform 4s cubic-bezier(0.15, 0.9, 0.2, 1);">
                    <canvas id="ruleta-canvas" width="320" height="320" class="w-full h-full"></canvas>
                </div>

                <!-- Centro Elegante de la Rueda (Eje Dorado Maderable) -->
                <div class="absolute z-20 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#FAF3E0] via-[#C09A75] to-[#88674B] rounded-full shadow-lg border-2 border-[#1F0F0B] flex items-center justify-center pointer-events-none">
                    <div class="w-4 h-4 rounded-full bg-[#1F0F0B] border border-amber-300/40 flex items-center justify-center text-[10px] text-amber-300">✨</div>
                </div>
            </div>

            <!-- Botón de Girar posicionado claramente ABAJO de la Ruleta -->
            <div class="mt-4 pt-1">
                <button id="ruleta-spin-btn" onclick="spinRuleta()" class="w-full bg-gradient-to-r from-amber-700 via-amber-800 to-amber-900 hover:from-amber-600 hover:to-amber-700 text-white font-black text-sm sm:text-base uppercase tracking-wider py-3.5 px-6 rounded-2xl shadow-xl hover:shadow-amber-900/50 transition-all flex items-center justify-center space-x-2 border border-amber-400/30 active:scale-95 group">
                    <span class="text-lg group-hover:rotate-12 transition-transform">🎰</span>
                    <span>¡GIRAR RULETA AHORA!</span>
                </button>
            </div>
        </div>

        <!-- Step Result: Premio Ganado -->
        <div id="ruleta-step-result" class="hidden py-4 space-y-4">
            <div class="text-4xl animate-bounce">🎁</div>
            <h3 class="serif-title text-2xl font-bold text-[#FAF3E0]">¡FELICIDADES!</h3>
            <p class="text-sm text-zinc-300">Has ganado este beneficio exclusivo:</p>

            <div class="bg-gradient-to-r from-[#88674B]/30 via-[#88674B]/20 to-[#88674B]/30 border border-[#88674B]/60 p-4 rounded-2xl">
                <span id="ruleta-result-titulo" class="serif-title text-2xl font-extrabold text-[#FAF3E0] block">--</span>
                <span class="text-xs text-amber-200 font-mono mt-1 block">Código: <span id="ruleta-result-codigo" class="font-bold">--</span></span>
            </div>

            <p class="text-xs text-zinc-400">
                Tienes <strong id="ruleta-result-tiempo" class="text-[#FAF3E0]">15 minutos</strong> para utilizarlo en tu carrito.
            </p>

            <form id="form-reclamar-ruleta" onsubmit="reclamarRuletaPremio(event)" class="pt-2">
                @csrf
                <input type="hidden" id="ruleta-input-posicion" name="posicion" value="1">
                <button type="submit" id="ruleta-claim-btn" class="w-full bg-gradient-to-r from-[#88674B] to-[#5C4331] hover:from-[#74563C] hover:to-[#2B241A] text-white font-bold text-sm uppercase tracking-wider py-3.5 px-6 rounded-xl shadow-lg hover:shadow-[#88674B]/30 transition-all flex items-center justify-center space-x-2">
                    <span>🎁 Reclamar y Aplicar al Carrito</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════ JAVASCRIPT GLOBAL ═══════════════ --}}
<script>
;(function(){
'use strict';
const SM = window.SM || {};

/* ── 1. Animación de vuelo al carrito ─────────────────── */
function animarVuelo(origen, imgSrc) {
    const cartEl = document.getElementById('nav-cart-icon');
    if (!cartEl) return;
    const oRect = origen.getBoundingClientRect();
    const cRect = cartEl.getBoundingClientRect();

    const sx = oRect.left + oRect.width  / 2;
    const sy = oRect.top  + oRect.height / 2;
    const ex = cRect.left + cRect.width  / 2;
    const ey = cRect.top  + cRect.height / 2;

    /* Punto de control para arco (arriba del trayecto) */
    const cpX = (sx + ex) / 2;
    const cpY = Math.min(sy, ey) - 160;

    const SIZE = 56;
    const el = document.createElement('div');
    el.style.cssText = [
        'position:fixed',
        `left:${sx - SIZE/2}px`,
        `top:${sy  - SIZE/2}px`,
        `width:${SIZE}px`,
        `height:${SIZE}px`,
        'border-radius:50%',
        'overflow:hidden',
        'border:3px solid white',
        'box-shadow:0 6px 24px rgba(0,0,0,.28)',
        'z-index:99999',
        'pointer-events:none',
        'will-change:transform,opacity',
        'background:#92400e',
        'display:flex',
        'align-items:center',
        'justify-content:center',
        'font-size:26px',
    ].join(';');

    if (imgSrc) {
        el.innerHTML = `<img src="${imgSrc}" style="width:100%;height:100%;object-fit:cover" loading="eager">`;
    } else {
        el.textContent = '🛋️';
    }
    document.body.appendChild(el);

    const DUR = 850;
    const t0  = performance.now();

    function step(now) {
        let p = Math.min((now - t0) / DUR, 1);
        /* easeInOut cúbico */
        const e = p < .5 ? 4*p*p*p : 1 - Math.pow(-2*p+2,3)/2;

        /* Curva de Bézier cuadrática */
        const bx = (1-e)*(1-e)*sx + 2*(1-e)*e*cpX + e*e*ex;
        const by = (1-e)*(1-e)*sy + 2*(1-e)*e*cpY + e*e*ey;

        const scale   = 1 - e * 0.78;
        const opacity = e > .72 ? 1 - (e - .72)/.28 : 1;

        el.style.transform = `translate(${bx-sx}px,${by-sy}px) scale(${scale})`;
        el.style.opacity    = opacity;

        if (p < 1) { requestAnimationFrame(step); }
        else {
            el.remove();
            /* Bounce del ícono del carrito */
            cartEl.classList.add('sm-cart-bounce');
            setTimeout(() => cartEl.classList.remove('sm-cart-bounce'), 600);
        }
    }
    requestAnimationFrame(step);
}

/* ── 2. Modal ─────────────────────────────────────────── */
SM.mostrarModal = function(nombre, imgSrc) {
    const modal   = document.getElementById('sm-cart-modal');
    const overlay = document.getElementById('sm-modal-overlay');
    const card    = document.getElementById('sm-modal-card');
    const img     = document.getElementById('sm-modal-img');
    const emoji   = document.getElementById('sm-modal-emoji');
    const nomEl   = document.getElementById('sm-modal-nombre');
    const check   = modal.querySelector('.sm-check-path');

    if (imgSrc) {
        img.src = imgSrc;
        img.classList.remove('hidden');
        if (emoji) emoji.classList.add('hidden');
    } else {
        img.classList.add('hidden');
        if (emoji) emoji.classList.remove('hidden');
    }
    if (nomEl) nomEl.textContent = nombre || '';
    if (check) check.classList.remove('drawn');

    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            card.style.opacity    = '1';
            card.style.transform  = 'scale(1) translateY(0)';
            if (check) setTimeout(() => check.classList.add('drawn'), 80);
        });
    });

    /* Cerrar con ESC */
    document._smEscFn = (ev) => { if (ev.key === 'Escape') SM.cerrarModal(); };
    document.addEventListener('keydown', document._smEscFn);
};

SM.cerrarModal = function() {
    const modal   = document.getElementById('sm-cart-modal');
    const overlay = document.getElementById('sm-modal-overlay');
    const card    = document.getElementById('sm-modal-card');

    overlay.style.opacity = '0';
    card.style.opacity    = '0';
    card.style.transform  = 'scale(.94) translateY(24px)';
    setTimeout(() => modal.classList.add('hidden'), 290);
    if (document._smEscFn) document.removeEventListener('keydown', document._smEscFn);
};

/* ── 3. Actualizar badge del carrito ─────────────────── */
function actualizarBadge(count) {
    const badges = [document.getElementById('cart-badge'), document.getElementById('mobile-cart-badge')];
    badges.forEach(badge => {
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
            badge.classList.remove('sm-badge-pop');
            void badge.offsetWidth; /* reflow para re-trigger */
            badge.classList.add('sm-badge-pop');
        }
    });
}

/* ── 4. Función principal interceptora ───────────────── */
SM.agregarCarrito = function(event, form) {
    event.preventDefault();

    const btn    = form.querySelector('button[type="submit"]');
    const nombre = form.dataset.nombre || 'Mueble';
    const imgSrc = form.dataset.img    || '';
    const DUR    = 850;

    /* Deshabilitar botón durante la petición */
    if (btn) { btn.disabled = true; btn.style.opacity = '0.65'; }

    /* Iniciar animación de vuelo inmediatamente */
    animarVuelo(btn || form, imgSrc);

    /* Enviar al servidor vía AJAX */
    const formData = new FormData(form);
    fetch(form.action, {
        method : 'POST',
        body   : formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        if (btn) { btn.disabled = false; btn.style.opacity = ''; }
        if (data.count !== undefined) actualizarBadge(data.count);
        /* Esperar a que termine el vuelo antes de mostrar modal */
        setTimeout(() => SM.mostrarModal(nombre, imgSrc), DUR - 80);
    })
    .catch(() => {
        /* Sin AJAX: envío tradicional de fallback */
        if (btn) { btn.disabled = false; btn.style.opacity = ''; }
        form.submit();
    });

    return false;
};

window.SM = SM;
})();
</script>

{{-- ═══════════════ SCRIPT RULETA INTERACTIVA ═══════════════ --}}
<script>
window.RULETA_OPCIONES = @json($ruletaOpcionesData);
window.RULETA_CUPON_SESION = @json($cuponSesion);

(function(){
    let currentRotation = 0;
    let isSpinning = false;
    let winningOption = null;

    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        initRuletaCanvas();
        initRuletaTimer();
        checkNewUserAutoOpen();
    });

    // Auto-apertura si es nuevo usuario y no ha jugado
    function checkNewUserAutoOpen() {
        const played = localStorage.getItem('sm_ruleta_played');
        const haJugadoBackend = @json($haJugadoRuleta);
        if (haJugadoBackend || played === 'true') {
            const triggerBtn = document.getElementById('ruleta-trigger-btn');
            if (triggerBtn) triggerBtn.style.display = 'none';
            return;
        }
        // Abrir automáticamente después de 1.5 segundos
        setTimeout(function() {
            openRuletaModal();
        }, 1500);
    }

    // Dibujar sectores en el Canvas con la paleta de colores oficial de Sector Mueble y resolución HD (Retina)
    function initRuletaCanvas() {
        const canvas = document.getElementById('ruleta-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const options = window.RULETA_OPCIONES || [];
        if (options.length === 0) return;

        // Alta Definición (Retina DPI Scaling) para texto 100% nítido en celulares
        const dpr = window.devicePixelRatio || 1;
        const displaySize = 320;
        canvas.width = displaySize * dpr;
        canvas.height = displaySize * dpr;
        ctx.scale(dpr, dpr);

        const numOptions = options.length;
        const arc = (2 * Math.PI) / numOptions;
        const cx = displaySize / 2;
        const cy = displaySize / 2;
        const radius = displaySize / 2;

        // Función para calcular brillo y asegurar contraste perfecto de lectura
        function getLuminance(hex) {
            if (!hex) return 200;
            let c = hex.replace('#', '');
            if (c.length === 3) c = c.split('').map(x => x + x).join('');
            const r = parseInt(c.substring(0, 2), 16) || 0;
            const g = parseInt(c.substring(2, 4), 16) || 0;
            const b = parseInt(c.substring(4, 6), 16) || 0;
            return (r * 299 + g * 587 + b * 114) / 1000;
        }

        // Dividir títulos largos inteligentemente en 2 líneas para máxima legibilidad
        function splitTitleIntoTwoLines(text) {
            if (!text) return { line1: '', line2: '' };
            text = text.trim();
            
            if (text.length <= 14) {
                return { line1: text, line2: '' };
            }

            const words = text.split(/\s+/);
            if (words.length <= 1) {
                return { line1: text, line2: '' };
            }

            let mid = Math.ceil(words.length / 2);

            // Regla especial para "15% OFF en tu primera compra" o "$500 Descuento Especial"
            if (words.length >= 3) {
                const w1Upper = words[1]?.toUpperCase() || '';
                const w0 = words[0];
                if (w1Upper === 'OFF' || w1Upper === 'GRATIS' || w1Upper === 'DESCUENTO') {
                    mid = 2;
                } else if (w0.includes('%') || w0.includes('$')) {
                    mid = 1;
                }
            }

            const line1 = words.slice(0, mid).join(' ');
            const line2 = words.slice(mid).join(' ');
            return { line1, line2 };
        }

        ctx.clearRect(0, 0, displaySize, displaySize);

        options.forEach((opt, idx) => {
            const angle = idx * arc;
            const sliceBg = opt.color_bg || '#FFF5EA';
            
            const lum = getLuminance(sliceBg);
            const isLightBg = lum > 140; // Cualquier fondo claro tendrá texto oscuro nítido

            // Selección de colores de texto con legibilidad óptima sobre fondo claro u oscuro
            const textColor = isLightBg ? '#1C120C' : '#FFFFFF';
            const subTextColor = isLightBg ? '#5C4331' : '#F5EBE6';
            const strokeColor = isLightBg ? '#FFFFFF' : '#0B0604';
            
            // 1. Dibujar sector de la rueda
            ctx.beginPath();
            ctx.fillStyle = sliceBg;
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, radius, angle, angle + arc);
            ctx.lineTo(cx, cy);
            ctx.fill();

            // 2. Línea divisoria crema/dorada brillante entre sectores
            ctx.lineWidth = 2.5;
            ctx.strokeStyle = '#88674B';
            ctx.stroke();

            // 3. Renderizar texto en 2 líneas si es necesario para máxima legibilidad
            ctx.save();
            ctx.translate(cx, cy);
            ctx.rotate(angle + arc / 2);
            ctx.textAlign = 'right';

            const rawLabel = opt.titulo || ('Opción ' + opt.posicion);
            const { line1, line2 } = splitTitleIntoTwoLines(rawLabel);

            if (line2) {
                // Línea 1 (Destacado principal: "15% OFF", "Envío Gratis", etc.)
                let fontSize1 = 13.5;
                if (line1.length > 18) fontSize1 = 11.5;
                ctx.font = `bold ${fontSize1}px 'Poppins', system-ui, -apple-system, sans-serif`;

                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = 3;
                ctx.lineJoin = 'round';
                ctx.strokeText(line1, radius - 18, -4);

                ctx.fillStyle = textColor;
                ctx.fillText(line1, radius - 18, -4);

                // Línea 2 (Subtexto: "en tu primera compra", "en tu pedido", etc.)
                let fontSize2 = 10.5;
                if (line2.length > 22) fontSize2 = 9.5;
                ctx.font = `600 ${fontSize2}px 'Poppins', system-ui, -apple-system, sans-serif`;

                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = 2.5;
                ctx.strokeText(line2, radius - 18, 12);

                ctx.fillStyle = subTextColor;
                ctx.fillText(line2, radius - 18, 12);
            } else {
                // Línea única
                let fontSize = 13;
                if (line1.length > 22) fontSize = 11;
                ctx.font = `bold ${fontSize}px 'Poppins', system-ui, -apple-system, sans-serif`;

                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = 3;
                ctx.strokeText(line1, radius - 18, 4);

                ctx.fillStyle = textColor;
                ctx.fillText(line1, radius - 18, 4);
            }

            ctx.restore();
        });

        // 4. Anillos exteriores de madera warm y borde dorado
        ctx.beginPath();
        ctx.arc(cx, cy, radius - 2, 0, 2 * Math.PI);
        ctx.lineWidth = 6;
        ctx.strokeStyle = '#88674B';
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(cx, cy, radius - 4, 0, 2 * Math.PI);
        ctx.lineWidth = 1.5;
        ctx.strokeStyle = '#FFFFFF';
        ctx.stroke();
    }

    // Sonidos sintetizados usando Web Audio API
    function playTickSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(600, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(120, ctx.currentTime + 0.05);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.05);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.05);
        } catch(e){}
    }

    function playFanfareSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const notes = [523.25, 659.25, 783.99, 1046.50];
            notes.forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.2, ctx.currentTime + i * 0.12);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.12 + 0.3);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + i * 0.12);
                osc.stop(ctx.currentTime + i * 0.12 + 0.35);
            });
        } catch(e){}
    }

    // Abrir Modal
    window.openRuletaModal = function() {
        const modal = document.getElementById('ruleta-modal');
        const card = document.getElementById('ruleta-modal-card');
        if (!modal || !card) return;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.style.opacity = '1';
            card.style.transform = 'scale(1)';
        }, 10);
    };

    // Cerrar Modal
    window.closeRuletaModal = function() {
        const modal = document.getElementById('ruleta-modal');
        const card = document.getElementById('ruleta-modal-card');
        if (!modal || !card) return;

        modal.style.opacity = '0';
        card.style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    };

    // Lógica del Giro
    window.spinRuleta = function() {
        if (isSpinning) return;
        const options = window.RULETA_OPCIONES || [];
        if (options.length === 0) return;

        isSpinning = true;
        const spinBtn = document.getElementById('ruleta-spin-btn');
        if (spinBtn) { spinBtn.disabled = true; spinBtn.style.opacity = '0.6'; }

        // Seleccionar ganador al azar (índice 0, 1 o 2)
        const numOptions = options.length;
        const winIdx = Math.floor(Math.random() * numOptions);
        winningOption = options[winIdx];

        // La aguja apunta a las 12 (270 grados = -90 grados).
        // Cada sector mide (360 / numOptions) grados.
        const sliceDeg = 360 / numOptions;
        // El centro del sector winIdx está en: winIdx * sliceDeg + (sliceDeg / 2)
        const sectorCenter = winIdx * sliceDeg + (sliceDeg / 2);
        // Queremos que dicho punto quede apuntando a 270 deg (o -90 deg)
        // Ángulo de alineación final dentro de [0, 360)
        const targetDeg = (270 - sectorCenter + 360) % 360;

        // Vueltas completas extra para la animación (5 vueltas = 1800 grados)
        const extraRotations = 5 * 360;
        
        // Calcular nueva rotación acumulada
        const currentMod = currentRotation % 360;
        let delta = targetDeg - currentMod;
        if (delta < 0) delta += 360;
        
        currentRotation += extraRotations + delta;

        const wrapper = document.getElementById('ruleta-wheel-wrapper');
        if (wrapper) {
            wrapper.style.transform = 'rotate(' + currentRotation + 'deg)';
        }

        // Ticks durante el giro
        let ticksCount = 0;
        const tickInterval = setInterval(() => {
            ticksCount++;
            playTickSound();
            if (ticksCount >= 18) clearInterval(tickInterval);
        }, 200);

        // Al finalizar la animación (4000 ms)
        setTimeout(function() {
            isSpinning = false;
            playFanfareSound();

            // Mostrar el premio ganado en la tarjeta
            document.getElementById('ruleta-step-spin').classList.add('hidden');
            document.getElementById('ruleta-step-result').classList.remove('hidden');

            document.getElementById('ruleta-result-titulo').innerText = winningOption.titulo;
            document.getElementById('ruleta-result-codigo').innerText = winningOption.codigo_cupon || ('RULETA' + winningOption.posicion);
            document.getElementById('ruleta-result-tiempo').innerText = winningOption.tiempo_minutos + ' minutos';
            document.getElementById('ruleta-input-posicion').value = winningOption.posicion;

            // Marcar que el usuario ya giró la ruleta
            localStorage.setItem('sm_ruleta_played', 'true');

        }, 4100);
    };

    // Reclamar Premio vía AJAX
    window.reclamarRuletaPremio = function(e) {
        e.preventDefault();
        const form = document.getElementById('form-reclamar-ruleta');
        const claimBtn = document.getElementById('ruleta-claim-btn');
        if (claimBtn) { claimBtn.disabled = true; claimBtn.innerText = 'Procesando...'; }

        const formData = new FormData(form);

        fetch('{{ route("ruleta.reclamar") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Marcar en localStorage que ya jugó
                localStorage.setItem('sm_ruleta_played', 'true');
                
                // Ocultar botón flotante permanentemente
                const triggerBtn = document.getElementById('ruleta-trigger-btn');
                if (triggerBtn) triggerBtn.style.display = 'none';

                // Actualizar banner sticky
                document.getElementById('ruleta-banner-titulo').innerText = data.titulo;
                document.getElementById('ruleta-sticky-banner').classList.remove('hidden');
                
                // Iniciar temporizador con el tiempo recibido
                startCountdownTimer(data.expira_en);

                closeRuletaModal();

                // Redirigir al carrito
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'Error al reclamar el premio.');
                if (claimBtn) { claimBtn.disabled = false; claimBtn.innerText = '🎁 Reclamar y Aplicar al Carrito'; }
            }
        })
        .catch(err => {
            console.error(err);
            if (claimBtn) { claimBtn.disabled = false; claimBtn.innerText = '🎁 Reclamar y Aplicar al Carrito'; }
        });
    };

    // Temporizador de cuenta regresiva
    let timerInterval = null;

    function initRuletaTimer() {
        const cupon = window.RULETA_CUPON_SESION;
        if (cupon && cupon.expira_en) {
            startCountdownTimer(cupon.expira_en);
        }
    }

    function startCountdownTimer(expireTimestamp) {
        if (timerInterval) clearInterval(timerInterval);

        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            const diff = expireTimestamp - now;

            const timerEl = document.getElementById('ruleta-banner-timer');

            if (diff <= 0) {
                if (timerEl) timerEl.innerText = 'Expirado';
                clearInterval(timerInterval);
                return;
            }

            const minutes = Math.floor(diff / 60);
            const seconds = diff % 60;

            const formatted = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            if (timerEl) timerEl.innerText = formatted;
        }

        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);
    }
})();
</script>

</html>


