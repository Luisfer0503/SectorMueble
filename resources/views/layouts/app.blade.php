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
    $haJugadoRuleta = (auth()->check() && auth()->user()->ruleta_jugada) || session('ruleta_jugada') || session()->has('cupon') || request()->cookie('sm_ruleta_played') === 'true';
@endphp

<!-- Banner Sticky de Premio Activo de Ruleta -->
<div id="ruleta-sticky-banner" class="{{ ($cuponSesion && isset($cuponSesion['expira_en'])) ? '' : 'hidden' }} relative z-30 bg-[#4c6f4f] text-white px-4 py-2.5 shadow-md border-b border-white/20">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm">
        <div class="flex items-center space-x-2 font-medium">
            <span class="animate-bounce text-base">🎁</span>
            <span><strong>¡Premio de Ruleta Activo!</strong> <span id="ruleta-banner-titulo">{{ $cuponSesion['titulo'] ?? ($cuponSesion['codigo'] ?? 'Descuento Especial') }}</span></span>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-black/25 px-3 py-1 rounded-full border border-white/30 flex items-center space-x-1.5 font-mono text-white">
                <svg class="w-4 h-4 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Expira en:</span>
                <strong id="ruleta-banner-timer" class="text-white font-bold text-sm">--:--</strong>
            </div>
            <a href="{{ route('carrito') }}" class="bg-[#3c583e] hover:bg-[#2d442f] text-white text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider transition-colors shadow border border-white/20">
                Ir al Carrito
            </a>
        </div>
    </div>
    <div class="max-w-7xl mx-auto text-center sm:text-right mt-1">
        <span class="text-[10px] text-white/80 italic">* Aplican términos y condiciones, no acumulable con otras promociones.</span>
    </div>
</div>

<!-- Banner Sticky de Notificación de Productos Esperando en Carrito -->
@if(session()->has('notificacion_carrito_abandonado') || (auth()->check() && !empty(auth()->user()->carrito_guardado) && session()->has('carrito') && count(session('carrito', [])) > 0 && !request()->routeIs('carrito') && !request()->routeIs('checkout')))
<div id="carrito-guardado-banner" class="relative z-30 bg-[#4c6f4f] text-white px-4 py-2.5 shadow-md border-b border-white/20">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm">
        <div class="flex items-center space-x-2 font-medium">
            <span class="animate-bounce text-base">🛒</span>
            <span><strong>¡Tus productos te están esperando!</strong> Dejamos guardados los muebles que tenías en tu carrito para que puedas completar tu compra.</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('carrito') }}" class="bg-[#3c583e] hover:bg-[#2d442f] text-white text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider transition-colors shadow border border-white/20">
                Ver mi Carrito ({{ array_sum(array_column(session('carrito', []), 'cantidad')) }})
            </a>
            <button type="button" onclick="document.getElementById('carrito-guardado-banner').remove()" class="text-white/80 hover:text-white text-xs font-bold px-1.5 py-0.5" title="Cerrar aviso">
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
            <div class="flex items-center justify-between h-16 sm:h-20 py-2 border-b border-zinc-150 gap-2 sm:gap-4">
                
                <!-- Botón Menú Móvil (Sólo en pantallas pequeñas) -->
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2.5 rounded-xl text-zinc-700 hover:text-[#88674B] hover:bg-amber-50 focus:outline-none transition-colors shrink-0" aria-label="Abrir menú">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Logos Principales (Izquierda - Caja SM en #8a7e72) -->
                <div class="flex-shrink-0 flex items-center space-x-1.5 sm:space-x-2.5 min-w-0 shrink-0">
                    <a href="{{ route('inicio') }}" class="flex items-center space-x-1.5 sm:space-x-2.5 group py-1 shrink-0">
                        <div class="relative hidden sm:flex items-center justify-center p-1 bg-[#8a7e72] rounded-xl border border-[#8a7e72]/40 shadow-sm shrink-0">
                            <img src="{{ asset('logo2.png') }}" alt="Sector Mueble Isotipo" class="h-6 sm:h-9 md:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-sm brand-logo-img" style="max-height: 40px; max-width: 120px;">
                        </div>
                        <img src="{{ asset('logo1.png') }}" alt="Sector Mueble Logotipo" class="h-7 sm:h-10 md:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-sm brand-logo-img shrink-0" style="max-height: 48px; max-width: 220px;">
                    </a>
                </div>

                <!-- Buscador Rápido en Centro (Desktop) -->
                <form action="{{ route('catalogo') }}" method="GET" class="hidden md:block relative flex-1 min-w-0 max-w-xs xl:max-w-md mx-3 xl:mx-6">
                    <input type="text" name="buscar" placeholder="Buscar muebles de diseño..." class="w-full bg-zinc-50 focus:bg-white text-xs px-4 py-2.5 pr-9 rounded-full border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50 focus:border-[#88674B] transition-all duration-300 shadow-inner">
                    <button type="submit" class="absolute right-3 top-2.5 text-zinc-400 hover:text-[#88674B] transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>

                <!-- Bloque Derecha: Acciones de usuario -->
                <div class="hidden md:flex items-center space-x-2 sm:space-x-3 flex-shrink-0 z-20">
                    
                    <!-- Botón Carrito de Compras (ÚNICAMENTE este botón en Verde Bosque #4c6f4f) -->
                    <a href="{{ route('carrito') }}" id="nav-cart-icon" class="relative flex items-center space-x-1.5 px-3 py-2 bg-[#4c6f4f] hover:bg-[#3c583e] text-white rounded-xl shadow transition-all duration-300 active:scale-95 flex-shrink-0 border border-white/20">
                        <svg class="h-4.5 w-4.5 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="text-xs font-bold whitespace-nowrap hidden sm:inline">Carrito</span>
                        @php
                            $cantidadCarrito = array_sum(array_column(session('carrito', []), 'cantidad'));
                        @endphp
                        <span id="cart-badge" class="{{ $cantidadCarrito > 0 ? '' : 'hidden' }} ml-1 px-1.5 py-0.5 text-[10px] sm:text-[11px] font-extrabold leading-none text-white bg-[#3c583e] rounded-full shadow">
                            {{ $cantidadCarrito }}
                        </span>
                    </a>

                    <!-- Usuario Autenticado / Sesión (ÚNICAMENTE el nombre de usuario en Verde Bosque #4c6f4f) -->
                    @auth
                        <div class="flex items-center space-x-2 border-l border-zinc-200 pl-2 sm:pl-3">
                            <span class="text-xs font-medium text-zinc-700 hidden sm:inline">Hola, <strong class="text-[#4c6f4f] font-extrabold">{{ auth()->user()->name }}</strong></span>
                            <a href="{{ route('perfil') }}" class="text-[11px] font-bold text-[#88674B] hover:text-[#74563C] hover:bg-amber-50 px-2 py-1 rounded-lg uppercase tracking-wider transition-colors">Mi Perfil</a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-[10px] font-bold text-white bg-[#88674B] hover:bg-[#74563C] px-2 py-1 rounded-lg uppercase tracking-wider">Admin</a>
                            @endif
                            <a href="{{ route('logout') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 px-2 py-1 rounded-lg hover:bg-rose-50 transition-colors uppercase tracking-wider">Salir</a>
                        </div>
                    @else
                        <div class="flex items-center space-x-1.5 sm:space-x-2 border-l border-zinc-200 pl-2 sm:pl-3">
                            <!-- Botón Iniciar Sesión (Beige) -->
                            <a href="{{ route('login') }}" class="flex items-center space-x-1 text-xs font-bold text-[#5C4033] bg-[#F5EBE0] hover:bg-[#E8DCCF] border border-[#D9C5B2] px-2.5 sm:px-3.5 py-1.5 rounded-xl transition-all shadow-xs whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 text-[#5C4033]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Iniciar Sesión</span>
                            </a>

                            <!-- Botón Registro (Verde Bosque #4c6f4f como Carrito) -->
                            <a href="{{ route('registro') }}" class="inline-flex items-center justify-center text-xs font-bold text-white bg-[#4c6f4f] hover:bg-[#3c583e] px-3.5 py-1.5 rounded-xl transition-all shadow-xs hover:shadow whitespace-nowrap border border-white/20">
                                <span>Registro</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- FILA 2: Barra de Navegación por Categorías + CP (A la misma altura que las categorías y debajo de Iniciar Sesión) -->
            <div class="hidden md:flex items-center justify-between border-t border-zinc-100/80 py-2 mt-1">
                
                <!-- Categorías (Izquierda) -->
                <nav class="flex items-center space-x-8">
                    <!-- SALA con Submenú Desplegable Estilo Marca -->
                    <div class="relative group">
                        <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="flex items-center space-x-1.5 text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-[#88674B] py-1 transition-colors cursor-pointer">
                            <span>Sala</span>
                            <svg class="w-3.5 h-3.5 text-zinc-500 group-hover:text-[#88674B] transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                        
                        <!-- Submenú flotante compacto -->
                        <div class="absolute top-full left-0 mt-1.5 w-64 bg-[#2B241A] text-[#FAF3E0] rounded-2xl shadow-2xl border border-[#88674B]/40 p-3 space-y-1 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform group-hover:translate-y-0 translate-y-2">
                            <!-- Opción para Ver Todos los productos de esta categoría -->
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="flex items-center justify-between px-3 py-2 text-xs font-extrabold text-white bg-[#88674B]/60 hover:bg-[#88674B] rounded-xl transition-colors mb-2 border border-[#88674B] shadow-xs">
                                <span>Ver todo en Sala</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Sofá']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Sofás y salas modulares</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Mesa']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Mesas de centro y laterales</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Sillón']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Sillones</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Credenza']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Credenzas</span>
                            </a>
                        </div>
                    </div>

                    <!-- RECÁMARA con Submenú Desplegable Estilo Marca -->
                    <div class="relative group">
                        <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="flex items-center space-x-1.5 text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-[#88674B] py-1 transition-colors cursor-pointer">
                            <span>Recámara</span>
                            <svg class="w-3.5 h-3.5 text-zinc-500 group-hover:text-[#88674B] transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>

                        <div class="absolute top-full left-0 mt-1.5 w-60 bg-[#2B241A] text-[#FAF3E0] rounded-2xl shadow-2xl border border-[#88674B]/40 p-3 space-y-1 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform group-hover:translate-y-0 translate-y-2">
                            <!-- Opción para Ver Todos los productos de esta categoría -->
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="flex items-center justify-between px-3 py-2 text-xs font-extrabold text-white bg-[#88674B]/60 hover:bg-[#88674B] rounded-xl transition-colors mb-2 border border-[#88674B] shadow-xs">
                                <span>Ver todo en Recámara</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio', 'buscar' => 'Base']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Camas</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio', 'buscar' => 'Buró']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Burós</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio', 'buscar' => 'Diván']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Divanes</span>
                            </a>
                        </div>
                    </div>

                    <!-- COMEDOR con Submenú Desplegable Estilo Marca -->
                    <div class="relative group">
                        <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="flex items-center space-x-1.5 text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-[#88674B] py-1 transition-colors cursor-pointer">
                            <span>Comedor</span>
                            <svg class="w-3.5 h-3.5 text-zinc-500 group-hover:text-[#88674B] transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>

                        <div class="absolute top-full left-0 mt-1.5 w-56 bg-[#2B241A] text-[#FAF3E0] rounded-2xl shadow-2xl border border-[#88674B]/40 p-3 space-y-1 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform group-hover:translate-y-0 translate-y-2">
                            <!-- Opción para Ver Todos los productos de esta categoría -->
                            <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="flex items-center justify-between px-3 py-2 text-xs font-extrabold text-white bg-[#88674B]/60 hover:bg-[#88674B] rounded-xl transition-colors mb-2 border border-[#88674B] shadow-xs">
                                <span>Ver todo en Comedor</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Sillas y Bancos']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Sillas</span>
                            </a>
                            <a href="{{ route('catalogo', ['categoria' => 'Comedor', 'buscar' => 'Mesa']) }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold rounded-xl hover:bg-[#88674B]/40 hover:text-[#FAF3E0] transition-colors">
                                <span class="text-[#C49A6C] font-bold">•</span>
                                <span>Mesas</span>
                            </a>
                        </div>
                    </div>

                    <!-- CATÁLOGO COMPLETO -->
                    <a href="{{ route('catalogo') }}" class="text-xs font-bold uppercase tracking-wider text-zinc-800 hover:text-[#88674B] py-1 transition-colors">
                        Catálogo Completo
                    </a>
                </nav>

                <!-- Botón de Código Postal (CP) con Fondo Azul Media Noche Semi-transparente #1E2440 -->
                <button type="button" onclick="abrirModalCP()" class="inline-flex items-center space-x-1.5 text-xs font-bold text-white bg-[#1E2440]/80 hover:bg-[#1E2440] backdrop-blur-md border border-white/20 px-3.5 py-2 rounded-full transition-all shadow-md group cursor-pointer" title="Consultar o cambiar tu Código Postal">
                    <svg class="w-3.5 h-3.5 text-white shrink-0 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="cp-header-text-span font-bold text-white">
                        @if(session('codigo_postal'))
                            CP: <strong>{{ session('codigo_postal') }}</strong>
                        @else
                            Ingresa tu CP
                        @endif
                    </span>
                    <span class="text-[10px] text-white/90 underline font-normal">(Cambiar)</span>
                </button>

            </div>
        </div>

        <!-- Menú Móvil Desplegable (Nogal Cálido #88674B) -->
        <div id="mobile-menu-drawer" class="hidden bg-[#88674B] text-white border-b border-white/20 px-4 pt-4 pb-6 space-y-4 shadow-xl">
            <form action="{{ route('catalogo') }}" method="GET" class="relative">
                <input type="text" name="buscar" placeholder="Buscar muebles de diseño..." class="w-full bg-white/95 text-zinc-900 text-xs px-4 py-2.5 pr-9 rounded-xl border border-white/30 focus:outline-none focus:ring-2 focus:ring-[#1E2440] placeholder-zinc-500">
                <button type="submit" class="absolute right-3 top-3 text-zinc-500 hover:text-zinc-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            <!-- Lista Vertical de Categorías con Acordeón Desplegable Exclusivo -->
            <div class="space-y-2 py-1" id="mobile-accordion-group">
                
                <!-- 1. SALAS -->
                <div class="border border-white/20 rounded-2xl overflow-hidden bg-black/15 transition-all">
                    <button type="button" onclick="toggleMobileAccordion('salas')" class="w-full flex items-center justify-between p-3.5 text-xs font-extrabold uppercase tracking-wider text-white hover:bg-white/10 transition-colors cursor-pointer">
                        <span class="flex items-center space-x-2">
                            <span>🛋️</span>
                            <span>Salas</span>
                        </span>
                        <svg id="acc-icon-salas" class="w-4 h-4 text-white/80 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="acc-content-salas" class="hidden bg-black/25 px-3 py-2 space-y-1.5 border-t border-white/10">
                        <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="flex items-center justify-between px-3 py-2 text-xs font-bold text-white bg-[#88674B]/80 hover:bg-[#88674B] rounded-xl transition-colors border border-white/20 shadow-xs">
                            <span>Ver todo en Salas</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Sofá']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Sofás y salas modulares</span>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Mesa']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Mesas de centro y laterales</span>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Sillón']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Sillones</span>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Salón', 'buscar' => 'Credenza']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Credenzas</span>
                        </a>
                    </div>
                </div>

                <!-- 2. RECÁMARAS -->
                <div class="border border-white/20 rounded-2xl overflow-hidden bg-black/15 transition-all">
                    <button type="button" onclick="toggleMobileAccordion('recamaras')" class="w-full flex items-center justify-between p-3.5 text-xs font-extrabold uppercase tracking-wider text-white hover:bg-white/10 transition-colors cursor-pointer">
                        <span class="flex items-center space-x-2">
                            <span>🛏️</span>
                            <span>Recámaras</span>
                        </span>
                        <svg id="acc-icon-recamaras" class="w-4 h-4 text-white/80 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="acc-content-recamaras" class="hidden bg-black/25 px-3 py-2 space-y-1.5 border-t border-white/10">
                        <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="flex items-center justify-between px-3 py-2 text-xs font-bold text-white bg-[#88674B]/80 hover:bg-[#88674B] rounded-xl transition-colors border border-white/20 shadow-xs">
                            <span>Ver todo en Recámaras</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Dormitorio', 'buscar' => 'Base']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Camas</span>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Dormitorio', 'buscar' => 'Buró']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Burós</span>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Dormitorio', 'buscar' => 'Diván']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Divanes</span>
                        </a>
                    </div>
                </div>

                <!-- 3. COMEDOR -->
                <div class="border border-white/20 rounded-2xl overflow-hidden bg-black/15 transition-all">
                    <button type="button" onclick="toggleMobileAccordion('comedor')" class="w-full flex items-center justify-between p-3.5 text-xs font-extrabold uppercase tracking-wider text-white hover:bg-white/10 transition-colors cursor-pointer">
                        <span class="flex items-center space-x-2">
                            <span>🪑</span>
                            <span>Comedor</span>
                        </span>
                        <svg id="acc-icon-comedor" class="w-4 h-4 text-white/80 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="acc-content-comedor" class="hidden bg-black/25 px-3 py-2 space-y-1.5 border-t border-white/10">
                        <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="flex items-center justify-between px-3 py-2 text-xs font-bold text-white bg-[#88674B]/80 hover:bg-[#88674B] rounded-xl transition-colors border border-white/20 shadow-xs">
                            <span>Ver todo en Comedor</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Sillas y Bancos']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Sillas</span>
                        </a>
                        <a href="{{ route('catalogo', ['categoria' => 'Comedor', 'buscar' => 'Mesa']) }}" class="flex items-center space-x-2 px-3 py-1.5 text-xs text-white/90 hover:text-white transition-colors">
                            <span class="text-[#FAF3E0]">•</span>
                            <span>Mesas</span>
                        </a>
                    </div>
                </div>

                <!-- 4. CATÁLOGO COMPLETO -->
                <div class="border border-white/20 rounded-2xl overflow-hidden bg-black/15 transition-all">
                    <a href="{{ route('catalogo') }}" class="flex items-center justify-between p-3.5 text-xs font-extrabold uppercase tracking-wider text-white hover:bg-white/10 transition-colors">
                        <span class="flex items-center space-x-2">
                            <span>✨</span>
                            <span>Catálogo Completo</span>
                        </span>
                        <svg class="w-4 h-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>

            <!-- Botón CP Móvil (Fondo Transparente) -->
            <button type="button" onclick="toggleMobileMenu(); abrirModalCP();" class="w-full flex items-center justify-center space-x-2 py-3 px-4 bg-[#1E2440]/80 hover:bg-[#1E2440] backdrop-blur-md text-white rounded-full border border-white/20 font-bold text-xs shadow-md cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="cp-header-text-span font-bold text-white">Consultar / Cambiar Código Postal</span>
            </button>

            @auth
                <div class="pt-2 border-t border-white/20 flex items-center justify-between text-xs text-white">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium">Hola, <strong class="font-extrabold">{{ auth()->user()->name }}</strong></span>
                        <a href="{{ route('perfil') }}" class="text-xs font-extrabold text-[#FAF3E0] underline hover:text-white">Mi Perfil</a>
                    </div>
                    <a href="{{ route('logout') }}" class="text-rose-200 hover:text-rose-100 font-bold">Cerrar Sesión</a>
                </div>
            @else
                <div class="pt-2 border-t border-white/20 flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="w-1/2 text-center py-2.5 bg-[#F5EBE0] text-[#5C4033] hover:bg-[#E8DCCF] border border-[#D9C5B2] rounded-xl font-extrabold text-xs shadow-xs transition-colors">Iniciar Sesión</a>
                    <a href="{{ route('registro') }}" class="w-1/2 text-center py-2.5 bg-[#4c6f4f] hover:bg-[#3c583e] text-white rounded-xl font-bold text-xs shadow-xs border border-white/20 transition-colors">Registro</a>
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

    function toggleMobileAccordion(key) {
        const keys = ['salas', 'recamaras', 'comedor'];
        keys.forEach(k => {
            const content = document.getElementById('acc-content-' + k);
            const icon = document.getElementById('acc-icon-' + k);
            if (k === key) {
                if (content) {
                    const isHidden = content.classList.contains('hidden');
                    if (isHidden) {
                        content.classList.remove('hidden');
                        if (icon) icon.classList.add('rotate-180');
                    } else {
                        content.classList.add('hidden');
                        if (icon) icon.classList.remove('rotate-180');
                    }
                }
            } else {
                if (content) content.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');
            }
        });
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
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="hover:text-[#FAF3E0] transition-colors">Salas</a></li>
                        <li><a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="hover:text-[#FAF3E0] transition-colors">Recámaras</a></li>
                        <li><a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="hover:text-[#FAF3E0] transition-colors">Comedor</a></li>
                        <li class="pt-1.5"><a href="{{ route('catalogo') }}" class="text-[#C49A6C] hover:text-white font-bold transition-colors">Ver Todo el Catálogo &rarr;</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Care -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Servicio al Cliente</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-[#FAF3E0] transition-colors">Políticas de Envío</a></li>
                        <li><a href="{{ route('terminos') }}" class="hover:text-[#FAF3E0] transition-colors">Términos y Condiciones</a></li>
                        <li><a href="{{ route('facturacion.index') }}" class="text-[#FAF3E0] hover:underline font-bold transition-colors">Facturación Electrónica SAT</a></li>
                        <li class="pt-2">
                            <a href="https://wa.me/5212226702641?text=Hola,%20quisiera%20más%20información%20sobre%20los%20muebles%20de%20Sector%20Mueble" target="_blank" rel="noopener noreferrer" class="flex items-center space-x-2 text-xs text-zinc-300 hover:text-[#FAF3E0] transition-colors group">
                                <svg class="w-4 h-4 text-[#C49A6C] group-hover:text-[#FAF3E0] shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h32a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM3 10a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8z"/>
                                </svg>
                                <span class="font-medium">Cel / WhatsApp: 222 670 2641</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:hola@sectormueble.com.mx" class="flex items-center space-x-2 text-xs text-zinc-300 hover:text-[#FAF3E0] transition-colors group">
                                <svg class="w-4 h-4 text-[#C49A6C] group-hover:text-[#FAF3E0] shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">hola@sectormueble.com.mx</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Newsletter -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Suscríbete</h3>
                    <p class="mt-4 text-sm text-zinc-400">Recibe 10% de descuento en tu primera compra y novedades exclusivas.</p>
                    <form action="#" class="mt-4 flex max-w-md">
                        <input type="email" placeholder="Tu correo electrónico" required class="w-full bg-zinc-800 text-white text-xs px-4 py-3 rounded-l border border-zinc-700 focus:outline-none focus:ring-1 focus:ring-[#C49A6C] focus:border-transparent">
                        <button type="submit" class="bg-[#88674B] hover:bg-[#74563C] text-white text-xs px-6 py-3 rounded-r font-medium transition-colors">Unirse</button>
                    </form>
                </div>
            </div>



            <!-- Bottom Area -->
            <div class="mt-8 pt-8 border-t border-zinc-800 flex flex-col md:flex-row items-center justify-between text-xs">
                <p>&copy; {{ date('Y') }} Sector Mueble. Todos los derechos reservados. Creado con pasión por el diseño.</p>
                <div class="flex items-center space-x-6 mt-4 md:mt-0">
                    <a href="https://www.facebook.com/people/Sector-Mueble/100064278152750/#" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <span>Facebook</span>
                    </a>
                    <a href="https://www.instagram.com/sectormueble.mx/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        <span>Instagram</span>
                    </a>
                    <a href="https://www.tiktok.com/@sectormueble.mx" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 15.68 6.34 6.34 0 0 0 9.33 22a6.34 6.34 0 0 0 6.34-6.34V9.37a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-.85-.8z"/></svg>
                        <span>TikTok</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Barra de Navegación Flotante Inferior para Celulares (Rectángulo Flotante Nogal Cálido #88674B) -->
    <div class="md:hidden fixed bottom-4 inset-x-4 z-[80] max-w-xs mx-auto">
        <nav class="bg-[#88674B] text-white rounded-2xl shadow-2xl border border-white/20 py-2.5 px-3 flex items-center justify-around">
            <!-- Inicio -->
            <a href="{{ route('inicio') }}" class="flex flex-col items-center space-y-0.5 text-[10px] font-bold transition-all {{ Route::is('inicio') ? 'text-white bg-white/25 px-2.5 py-1 rounded-xl shadow-xs scale-105' : 'text-white/80 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 001 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Inicio</span>
            </a>

            <!-- Catálogo -->
            <a href="{{ route('catalogo') }}" class="flex flex-col items-center space-y-0.5 text-[10px] font-bold transition-all {{ Route::is('catalogo') ? 'text-white bg-white/25 px-2.5 py-1 rounded-xl shadow-xs scale-105' : 'text-white/80 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                </svg>
                <span>Catálogo</span>
            </a>

            <!-- Carrito -->
            <a href="{{ route('carrito') }}" class="relative flex flex-col items-center space-y-0.5 text-[10px] font-bold transition-all {{ Route::is('carrito') ? 'text-white bg-white/25 px-2.5 py-1 rounded-xl shadow-xs scale-105' : 'text-white/80 hover:text-white' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span id="mobile-cart-badge" class="{{ $cantidadCarrito > 0 ? '' : 'hidden' }} absolute -top-1.5 -right-2 bg-emerald-600 text-white text-[9px] font-black rounded-full h-4 w-4 flex items-center justify-center shadow border border-white">
                        {{ $cantidadCarrito }}
                    </span>
                </div>
                <span>Carrito</span>
            </a>

            <!-- Usuario / Perfil -->
            @auth
                <a href="{{ route('perfil') }}" class="flex flex-col items-center space-y-0.5 text-[10px] font-bold transition-all {{ Route::is('perfil') ? 'text-white bg-white/25 px-2.5 py-1 rounded-xl shadow-xs scale-105' : 'text-white/80 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Mi Perfil</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center space-y-0.5 text-[10px] font-bold transition-all {{ Route::is('login') ? 'text-white bg-white/25 px-2.5 py-1 rounded-xl shadow-xs scale-105' : 'text-white/80 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Ingresar</span>
                </a>
            @endauth
        </nav>
    </div>

    <!-- Botón Flotante Fijo de WhatsApp (Lado Izquierdo) -->
    <a href="https://wa.me/5212226702641?text=Hola,%20quisiera%20más%20información%20sobre%20los%20muebles%20de%20Sector%20Mueble" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="fixed bottom-20 md:bottom-6 left-5 sm:left-6 z-[9990] flex items-center justify-center rounded-full transition-transform duration-300 hover:scale-110 active:scale-95 group"
       style="background-color: #25D366 !important; width: 56px; height: 56px; box-shadow: 0 10px 25px rgba(37, 211, 102, 0.45) !important;"
       title="¿Necesitas ayuda? Chatea con nosotros por WhatsApp">
        
        <!-- Indicador de pulso activo -->
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-300 border-2 border-white"></span>
        </span>

        <!-- Tooltip emergente hacia la derecha -->
        <span class="absolute left-full ml-3 bg-zinc-950/90 text-white text-xs font-bold py-2 px-3.5 rounded-2xl shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none hidden sm:flex items-center space-x-1.5" style="backdrop-filter: blur(8px);">
            <span>¡Hola! ¿Dudas con tu mueble? Chatea aquí</span>
            <span class="text-base">💬</span>
        </span>

        <!-- Ícono oficial blanco de WhatsApp -->
        <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 24 24" style="width: 32px; height: 32px; fill: #ffffff !important;">
            <path fill="#ffffff" d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.765.459 3.488 1.332 5.006l-1.417 5.176 5.302-1.39a9.923 9.923 0 0 0 4.77 1.214h.004c5.505 0 9.988-4.478 9.989-9.985 0-2.668-1.037-5.176-2.922-7.062a9.925 9.925 0 0 0-7.066-2.943zm5.666 14.237c-.247.691-1.442 1.32-1.996 1.405-.512.079-1.18.113-3.398-.804-2.836-1.173-4.66-4.062-4.802-4.25-.141-.188-1.144-1.523-1.144-2.906 0-1.383.72-2.062.977-2.344.257-.282.564-.352.752-.352.188 0 .376.002.538.009.172.008.403-.065.63.48.236.568.804 1.96.874 2.102.07.142.117.309.023.497-.094.188-.141.305-.282.47-.141.164-.296.368-.423.494-.141.141-.288.294-.124.576.164.282.729 1.203 1.564 1.947 1.074.957 1.98 1.254 2.262 1.395.282.141.446.117.61-.07.165-.188.705-.822.893-1.104.188-.282.376-.235.635-.141.258.094 1.643.775 1.925.916.282.141.446.117.54.329.07.117.07.681-.177 1.372z"/>
        </svg>
    </a>

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
<button id="ruleta-trigger-btn" onclick="openRuletaModal()" class="hidden fixed bottom-20 md:bottom-6 left-4 sm:left-6 z-40 bg-[#1E2440] hover:bg-[#151a30] text-white p-3 sm:p-3.5 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 flex items-center space-x-2 border-2 border-white/20 group">
    <span class="text-xl animate-bounce">🎡</span>
    <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline-block pr-1 text-white">Ruleta de Bienvenida</span>
</button>

<!-- Modal de Ruleta de Premios para Nuevos Usuarios -->
<div id="ruleta-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" style="opacity: 0; transition: opacity 0.3s ease;">
    <!-- Backdrop Overlay -->
    <div class="absolute inset-0 bg-zinc-950/80 backdrop-blur-md" onclick="closeRuletaModal()"></div>

    <!-- Container Card con Fondo Nogal Cálido #C49A6C -->
    <div class="relative bg-[#C49A6C] border-2 border-white/20 rounded-3xl shadow-2xl max-w-md w-full p-6 text-white text-center overflow-hidden transform scale-95 transition-transform duration-300" id="ruleta-modal-card">
        <!-- Glow accents -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-black/40 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Botón cerrar -->
        <button onclick="closeRuletaModal()" class="absolute top-4 right-4 text-zinc-300 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors z-10">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header Modal -->
        <div id="ruleta-step-spin">
            <div class="mb-4">
                <span class="inline-block bg-white/20 border border-white/30 text-white text-[11px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest mb-2">
                    ✨ Exclusivo Nuevos Clientes ✨
                </span>
                <h3 class="serif-title text-2xl sm:text-3xl font-bold text-white tracking-wide">
                    ¡Gira la Ruleta de Sector Mueble!
                </h3>
                <p class="text-xs text-white/90 mt-1">
                    Obtén un cupón exclusivo para tu primera compra de muebles de diseño.
                </p>
                <p class="text-[10px] text-white/80 italic mt-1">* Aplican términos y condiciones, no acumulable con otras promociones.</p>
            </div>

            <!-- Canvas contenedor de la rueda -->
            <div class="relative mx-auto my-3 w-[270px] h-[270px] sm:w-[300px] sm:h-[300px] flex items-center justify-center">
                <!-- Flecha Indicadora Superior -->
                <div class="absolute -top-3 z-30 flex flex-col items-center">
                    <div class="w-0 h-0 border-l-[14px] border-l-transparent border-r-[14px] border-r-transparent border-t-[22px] border-t-amber-400 drop-shadow-[0_4px_8px_rgba(245,158,11,0.6)]"></div>
                </div>

                <!-- Rueda Canvas -->
                <div id="ruleta-wheel-wrapper" class="w-full h-full rounded-full shadow-[0_0_35px_rgba(0,0,0,0.4)] border-4 border-white/30 overflow-hidden relative" style="transition: transform 4s cubic-bezier(0.15, 0.9, 0.2, 1);">
                    <canvas id="ruleta-canvas" width="320" height="320" class="w-full h-full"></canvas>
                </div>

                <!-- Centro Elegante de la Rueda (Eje Dorado Maderable) -->
                <div class="absolute z-20 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#FAF3E0] via-[#C09A75] to-[#88674B] rounded-full shadow-lg border-2 border-[#C49A6C] flex items-center justify-center pointer-events-none">
                    <div class="w-4 h-4 rounded-full bg-[#C49A6C] border border-amber-300/40 flex items-center justify-center text-[10px] text-amber-300">✨</div>
                </div>
            </div>

            <!-- Botón de Girar en Azul Media Noche #1E2440 -->
            <div class="mt-4 pt-1">
                <button id="ruleta-spin-btn" onclick="spinRuleta()" class="w-full bg-[#1E2440] hover:bg-[#151a30] text-white font-black text-sm sm:text-base uppercase tracking-wider py-3.5 px-6 rounded-2xl shadow-xl hover:shadow-[#1E2440]/50 transition-all flex items-center justify-center space-x-2 border border-white/20 active:scale-95 group">
                    <span class="text-lg group-hover:rotate-12 transition-transform">🎰</span>
                    <span>¡GIRAR RULETA AHORA!</span>
                </button>
            </div>
        </div>

        <!-- Step Result: Premio Ganado -->
        <div id="ruleta-step-result" class="hidden py-4 space-y-4">
            <div class="text-4xl animate-bounce">🎁</div>
            <h3 class="serif-title text-2xl font-bold text-white">¡FELICIDADES!</h3>
            <p class="text-sm text-white/90">Has ganado este beneficio exclusivo:</p>

            <div class="bg-white/15 border border-white/25 p-4 rounded-2xl">
                <span id="ruleta-result-titulo" class="serif-title text-2xl font-extrabold text-white block">--</span>
                <span class="text-xs text-amber-200 font-mono mt-1 block">Código: <span id="ruleta-result-codigo" class="font-bold">--</span></span>
            </div>

            <p class="text-xs text-white/80">
                Tienes <strong id="ruleta-result-tiempo" class="text-white">15 minutos</strong> para utilizarlo en tu carrito.
            </p>
            <p class="text-[10px] text-white/80 italic mt-1">* Aplican términos y condiciones, no acumulable con otras promociones.</p>

            <form id="form-reclamar-ruleta" onsubmit="reclamarRuletaPremio(event)" class="pt-2">
                @csrf
                <input type="hidden" id="ruleta-input-posicion" name="posicion" value="1">
                <button type="submit" id="ruleta-claim-btn" class="w-full bg-[#1E2440] hover:bg-[#151a30] text-white font-bold text-sm uppercase tracking-wider py-3.5 px-6 rounded-xl shadow-lg transition-all flex items-center justify-center space-x-2 border border-white/20">
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

    function markRuletaPlayed() {
        localStorage.setItem('sm_ruleta_played', 'true');
        document.cookie = "sm_ruleta_played=true; max-age=31536000; path=/; SameSite=Lax";
        const triggerBtn = document.getElementById('ruleta-trigger-btn');
        if (triggerBtn) triggerBtn.style.display = 'none';
    }

    // Auto-apertura si es nuevo usuario y no ha jugado
    function checkNewUserAutoOpen() {
        const playedLS = localStorage.getItem('sm_ruleta_played');
        const playedCookie = document.cookie.split('; ').some(c => c.trim().startsWith('sm_ruleta_played=true'));
        const haJugadoBackend = @json($haJugadoRuleta);

        if (haJugadoBackend || playedLS === 'true' || playedCookie) {
            const triggerBtn = document.getElementById('ruleta-trigger-btn');
            if (triggerBtn) triggerBtn.style.display = 'none';
            return;
        }

        const triggerBtn = document.getElementById('ruleta-trigger-btn');
        if (triggerBtn) triggerBtn.classList.remove('hidden');

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

        markRuletaPlayed();
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
            markRuletaPlayed();

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
                // Marcar que ya jugó
                markRuletaPlayed();
                
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
    // Función global para cambiar la imagen del producto al seleccionar una combinación (portada)
    window.cambiarImagenCard = function(btn, imgId, newSrc, subId, formId) {
        if (!newSrc || newSrc.trim() === '') return;

        const imgEl = document.getElementById(imgId);
        if (imgEl) {
            imgEl.src = newSrc;
            imgEl.setAttribute('src', newSrc);
            imgEl.style.transition = 'opacity 0.15s ease-in-out, transform 0.15s ease-in-out';
            imgEl.style.opacity = '0.4';
            imgEl.style.transform = 'scale(0.97)';

            setTimeout(() => {
                imgEl.style.opacity = '1';
                imgEl.style.transform = 'scale(1)';
            }, 100);
        }

        const secImgEl = document.getElementById('sec-' + imgId);
        if (secImgEl) {
            secImgEl.style.display = 'none';
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
})();
</script>

</html>


