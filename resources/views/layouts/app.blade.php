<!DOCTYPE html>
<html lang="es" class="h-full bg-[#FAF3E0]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Sector Mueble | E-commerce de Muebles de Diseño')</title>
    <meta name="description" content="Encuentra los mejores muebles de diseño escandinavo, industrial y moderno para tu hogar u oficina en Sector Mueble. Envíos a todo el país.">
    
    <!-- Styles and Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', 'Eras ITC', 'Metropolis', sans-serif;
        }
        .serif-title, h1, h2, h3, .font-heading {
            font-family: 'NT Fabulous', 'Poppins', sans-serif;
        }
        .font-subtitle, .subtitle-brand {
            font-family: 'NT Fabulous Alternative', 'Poppins', sans-serif;
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

@php
    $ruletaOpcionesData = \App\Models\RuletaOpcion::where('activo', true)->orderBy('posicion', 'asc')->get();
    $cuponSesion = session()->get('cupon');
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

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-zinc-200 shadow-sm" onmouseleave="closeSubnav()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Fila Única: Logos (Izquierda), 3 Categorías (Centro Distribuido), Acciones (Derecha) -->
            <div class="flex items-center justify-between h-20 sm:h-24 py-2">
                
                <!-- Logos (Izquierda) -->
                <div class="flex-shrink-0">
                    <a href="{{ route('inicio') }}" class="flex items-center space-x-2 group">
                        <img src="{{ asset('logo2.png') }}" alt="Logo 2" class="h-9 sm:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                        <img src="{{ asset('logo1.png') }}" alt="Logo 1" class="h-11 sm:h-15 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </a>
                </div>

                <!-- Pestañas de Navegación Distribuidas en el Centro -->
                <nav class="hidden md:flex items-center justify-center space-x-12 lg:space-x-16 xl:space-x-24 px-4">
                    <button onclick="switchSubnav('sala')" onmouseenter="switchSubnav('sala')" id="tab-sala" class="subnav-tab text-zinc-800 hover:text-red-700 border-b-2 border-transparent pb-1 px-2 tracking-wider transition-all cursor-pointer whitespace-nowrap text-xs lg:text-sm font-bold uppercase">
                        Sala
                    </button>
                    <button onclick="switchSubnav('recamara')" onmouseenter="switchSubnav('recamara')" id="tab-recamara" class="subnav-tab text-zinc-800 hover:text-red-700 border-b-2 border-transparent pb-1 px-2 tracking-wider transition-all cursor-pointer whitespace-nowrap text-xs lg:text-sm font-bold uppercase">
                        Recámara
                    </button>
                    <button onclick="switchSubnav('comedor')" onmouseenter="switchSubnav('comedor')" id="tab-comedor" class="subnav-tab text-zinc-800 hover:text-red-700 border-b-2 border-transparent pb-1 px-2 tracking-wide transition-all cursor-pointer whitespace-nowrap text-xs lg:text-sm font-bold uppercase">
                        Comedor
                    </button>
                </nav>

                <!-- Bloque Derecha: Acciones de usuario -->
                <div class="flex items-center space-x-4 flex-shrink-0">
                    <!-- Buscador rápido en desktop -->
                    <form action="{{ route('catalogo') }}" method="GET" class="hidden lg:block relative">
                        <input type="text" name="buscar" placeholder="Buscar muebles..." class="w-44 bg-zinc-50 focus:bg-white text-xs px-4 py-2 pr-8 rounded-full border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:border-transparent transition-all duration-300">
                        <button type="submit" class="absolute right-3 top-2.5 text-zinc-400 hover:text-amber-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Carrito -->
                    <a href="{{ route('carrito') }}" id="nav-cart-icon" class="relative p-2 text-zinc-600 hover:text-amber-800 transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @php
                            $cantidadCarrito = array_sum(array_column(session('carrito', []), 'cantidad'));
                        @endphp
                        <span id="cart-badge" class="{{ $cantidadCarrito > 0 ? '' : 'hidden' }} absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/3 -translate-y-1/3 bg-amber-800 rounded-full">
                            {{ $cantidadCarrito }}
                        </span>
                    </a>

                    <!-- Usuario Autenticado / Sesión -->
                    @auth
                        <div class="flex items-center space-x-3 ml-2 border-l border-zinc-200 pl-4">
                            <span class="text-xs font-medium text-zinc-650">Hola, <span class="text-amber-800 font-bold">{{ auth()->user()->name }}</span></span>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-[10px] font-bold text-zinc-700 bg-zinc-150 hover:bg-zinc-200 hover:text-zinc-900 px-2 py-1 rounded transition-all uppercase tracking-wider">Panel Admin</a>
                            @endif
                            <a href="{{ route('logout') }}" class="text-xs font-semibold text-zinc-500 hover:text-rose-650 transition-colors uppercase tracking-wider">Salir</a>
                        </div>
                    @else
                        <div class="flex items-center space-x-3 ml-2 border-l border-zinc-200 pl-4">
                            <a href="{{ route('login') }}" class="text-xs font-semibold text-zinc-600 hover:text-amber-800 transition-colors uppercase tracking-wider">Entrar</a>
                            <a href="{{ route('registro') }}" class="text-xs font-bold text-white bg-amber-800 hover:bg-amber-700 px-3.5 py-1.5 rounded transition-all shadow-sm">Registro</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Row 2: Subnav Panel Desglosado Únicamente para los 3 apartados separadamente -->
        <div id="mega-subnav-panel" class="hidden bg-[#F8F8F8] border-t border-zinc-200 py-5 transition-all duration-300 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Panel 1: SALA (Lista vertical con viñetas sin titulo superior) -->
                <div id="panel-sala" class="subnav-content-panel hidden py-2">
                    <ul class="max-w-xs mx-auto space-y-2.5 text-zinc-800 font-medium text-sm text-left">
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="hover:text-red-700 transition-colors">Sofás y salas modulares</a>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="hover:text-red-700 transition-colors">Mesas de centro y laterales</a>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="hover:text-red-700 transition-colors">Sillones</a>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="hover:text-red-700 transition-colors">Credenzas</a>
                        </li>
                    </ul>
                </div>

                <!-- Panel 2: RECÁMARA (Lista vertical con viñetas sin titulo superior) -->
                <div id="panel-recamara" class="subnav-content-panel hidden py-2">
                    <ul class="max-w-xs mx-auto space-y-2.5 text-zinc-800 font-medium text-sm text-left">
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="hover:text-red-700 transition-colors">Camas</a>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="hover:text-red-700 transition-colors">Burós</a>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="hover:text-red-700 transition-colors">Divanes</a>
                        </li>
                    </ul>
                </div>

                <!-- Panel 3: COMEDOR (Lista vertical con viñetas sin titulo superior) -->
                <div id="panel-comedor" class="subnav-content-panel hidden py-2">
                    <ul class="max-w-xs mx-auto space-y-2.5 text-zinc-800 font-medium text-sm text-left">
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="hover:text-red-700 transition-colors">Sillas</a>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="text-zinc-950 font-bold select-none">•</span>
                            <a href="{{ route('catalogo', ['categoria' => 'Comedor']) }}" class="hover:text-red-700 transition-colors">Mesas</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </header>

    <script>
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
    <main class="flex-grow">
        @yield('contenido')
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-900 text-zinc-400 border-t border-zinc-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Logo & Desc -->
                <div>
                    <a href="{{ route('inicio') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('logo2.png') }}" alt="Logo 2" class="h-10 w-auto object-contain brightness-0 invert">
                        <img src="{{ asset('logo1.png') }}" alt="Logo 1" class="h-12 w-auto object-contain brightness-0 invert">
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

            <!-- Bottom Area -->
            <div class="mt-12 pt-8 border-t border-zinc-800 flex flex-col md:flex-row items-center justify-between text-xs">
                <p>&copy; {{ date('Y') }} Sector Mueble S.L. Todos los derechos reservados. Creado con pasión por el diseño.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <span class="hover:text-white transition-colors cursor-pointer">Instagram</span>
                    <span class="hover:text-white transition-colors cursor-pointer">Pinterest</span>
                    <span class="hover:text-white transition-colors cursor-pointer">Facebook</span>
                </div>
            </div>
        </div>
    </footer>

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

<!-- Botón Flotante para abrir la Ruleta -->
<button id="ruleta-trigger-btn" onclick="openRuletaModal()" class="fixed bottom-6 left-6 z-40 bg-gradient-to-r from-amber-600 via-amber-700 to-amber-800 text-white p-3.5 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 flex items-center space-x-2 border-2 border-amber-300/50 group">
    <span class="text-xl animate-bounce">🎡</span>
    <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline-block pr-1">Ruleta de Premios</span>
</button>

<!-- Modal de Ruleta de Premios para Nuevos Usuarios -->
<div id="ruleta-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" style="opacity: 0; transition: opacity 0.3s ease;">
    <!-- Backdrop Overlay -->
    <div class="absolute inset-0 bg-zinc-950/75 backdrop-blur-md" onclick="closeRuletaModal()"></div>

    <!-- Container Card -->
    <div class="relative bg-zinc-900 border-2 border-amber-500/40 rounded-3xl shadow-2xl max-w-md w-full p-6 text-white text-center overflow-hidden transform scale-95 transition-transform duration-300" id="ruleta-modal-card">
        <!-- Glow accents -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-amber-700/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Botón cerrar -->
        <button onclick="closeRuletaModal()" class="absolute top-4 right-4 text-zinc-400 hover:text-white p-2 rounded-full hover:bg-zinc-800 transition-colors z-10">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header Modal -->
        <div id="ruleta-step-spin">
            <div class="mb-4">
                <span class="inline-block bg-amber-500/20 border border-amber-400/40 text-amber-300 text-[11px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest mb-2">
                    ✨ Exclusivo Nuevos Clientes ✨
                </span>
                <h3 class="serif-title text-2xl sm:text-3xl font-bold text-white tracking-wide">
                    ¡Gira la Ruleta de Bienvenida!
                </h3>
                <p class="text-xs text-zinc-300 mt-1">
                    Obtén un descuento o beneficio especial para tu compra hoy.
                </p>
            </div>

            <!-- Canvas contenedor de la rueda -->
            <div class="relative mx-auto my-6 w-[280px] h-[280px] sm:w-[320px] sm:h-[320px] flex items-center justify-center">
                <!-- Flecha Indicadora Superior -->
                <div class="absolute -top-3 z-30 flex flex-col items-center">
                    <div class="w-0 h-0 border-l-[14px] border-l-transparent border-r-[14px] border-r-transparent border-t-[22px] border-t-amber-400 drop-shadow-[0_4px_8px_rgba(245,158,11,0.6)]"></div>
                </div>

                <!-- Rueda Canvas -->
                <div id="ruleta-wheel-wrapper" class="w-full h-full rounded-full shadow-[0_0_35px_rgba(217,119,6,0.35)] border-4 border-amber-400/80 overflow-hidden relative" style="transition: transform 4s cubic-bezier(0.15, 0.9, 0.2, 1);">
                    <canvas id="ruleta-canvas" width="320" height="320" class="w-full h-full"></canvas>
                </div>

                <!-- Center Spin Button -->
                <button id="ruleta-spin-btn" onclick="spinRuleta()" class="absolute z-20 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-amber-400 via-amber-500 to-amber-600 text-zinc-950 font-black text-xs sm:text-sm uppercase tracking-wider rounded-full shadow-[0_0_20px_rgba(245,158,11,0.8)] border-4 border-zinc-900 flex items-center justify-center hover:scale-105 active:scale-95 transition-all">
                    ¡GIRAR!
                </button>
            </div>
        </div>

        <!-- Step Result: Premio Ganado -->
        <div id="ruleta-step-result" class="hidden py-4 space-y-4">
            <div class="text-4xl animate-bounce">🎉</div>
            <h3 class="serif-title text-2xl font-bold text-amber-400">¡FELICIDADES!</h3>
            <p class="text-sm text-zinc-300">Has ganado este beneficio exclusivo:</p>

            <div class="bg-gradient-to-r from-amber-500/20 via-amber-500/10 to-amber-500/20 border border-amber-400/40 p-4 rounded-2xl">
                <span id="ruleta-result-titulo" class="serif-title text-2xl font-extrabold text-white block">--</span>
                <span class="text-xs text-amber-300 font-mono mt-1 block">Código: <span id="ruleta-result-codigo" class="font-bold">--</span></span>
            </div>

            <p class="text-xs text-zinc-400">
                Tienes <strong id="ruleta-result-tiempo" class="text-amber-300">15 minutos</strong> para utilizarlo en tu carrito.
            </p>

            <form id="form-reclamar-ruleta" onsubmit="reclamarRuletaPremio(event)" class="pt-2">
                @csrf
                <input type="hidden" id="ruleta-input-posicion" name="posicion" value="1">
                <button type="submit" id="ruleta-claim-btn" class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-zinc-950 font-bold text-sm uppercase tracking-wider py-3.5 px-6 rounded-xl shadow-lg hover:shadow-amber-500/30 transition-all flex items-center justify-center space-x-2">
                    <span>🎁 Reclamar y Aplicar al Carrito</span>
                </button>
            </form>
        </div>
    </div>
</div>

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
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
        badge.classList.remove('sm-badge-pop');
        void badge.offsetWidth; /* reflow para re-trigger */
        badge.classList.add('sm-badge-pop');
    }
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
        if (!played) {
            // Abrir automáticamente después de 1.5 segundos
            setTimeout(function() {
                openRuletaModal();
            }, 1500);
        }
    }

    // Dibujar sectores en el Canvas
    function initRuletaCanvas() {
        const canvas = document.getElementById('ruleta-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const options = window.RULETA_OPCIONES || [];
        if (options.length === 0) return;

        const numOptions = options.length;
        const arc = (2 * Math.PI) / numOptions;
        const cx = canvas.width / 2;
        const cy = canvas.height / 2;
        const radius = canvas.width / 2;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        options.forEach((opt, idx) => {
            const angle = idx * arc;
            
            // Sector background
            ctx.beginPath();
            ctx.fillStyle = opt.color_bg || '#B45309';
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, radius, angle, angle + arc);
            ctx.lineTo(cx, cy);
            ctx.fill();

            // Sector border line
            ctx.lineWidth = 3;
            ctx.strokeStyle = '#F59E0B';
            ctx.stroke();

            // Texto en el sector
            ctx.save();
            ctx.translate(cx, cy);
            ctx.rotate(angle + arc / 2);
            ctx.textAlign = 'right';
            ctx.fillStyle = '#FFFFFF';
            ctx.font = 'bold 13px Poppins, sans-serif';
            
            // Acortar texto si es muy largo
            let label = opt.titulo || ('Opción ' + opt.posicion);
            if (label.length > 22) label = label.substring(0, 20) + '..';

            ctx.fillText(label, radius - 20, 5);
            ctx.restore();
        });
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
