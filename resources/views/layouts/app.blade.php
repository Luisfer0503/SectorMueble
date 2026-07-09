<!DOCTYPE html>
<html lang="es" class="h-full bg-[#FAF9F6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Sector Mueble | E-commerce de Muebles de Diseño')</title>
    <meta name="description" content="Encuentra los mejores muebles de diseño escandinavo, industrial y moderno para tu hogar u oficina en Sector Mueble. Envíos a todo el país.">
    
    <!-- Google Fonts for premium serif headings -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles and Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', var(--font-sans), sans-serif;
        }
        .serif-title {
            font-family: 'Playfair Display', serif;
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

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-zinc-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('inicio') }}" class="flex items-center space-x-2">
                        <span class="serif-title text-2xl font-bold tracking-wider text-zinc-900">SECTOR<span class="text-amber-800">MUEBLE</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('inicio') }}" class="text-sm font-medium {{ Route::is('inicio') ? 'text-amber-800 border-b-2 border-amber-800 pb-1' : 'text-zinc-600 hover:text-zinc-900' }}">
                        Inicio
                    </a>
                    <a href="{{ route('catalogo') }}" class="text-sm font-medium {{ Route::is('catalogo') || Route::is('productos.detalle') ? 'text-amber-800 border-b-2 border-amber-800 pb-1' : 'text-zinc-600 hover:text-zinc-900' }}">
                        Catálogo
                    </a>
                    <a href="{{ route('catalogo', ['categoria' => 'Salón']) }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900">Salón</a>
                    <a href="{{ route('catalogo', ['categoria' => 'Dormitorio']) }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900">Dormitorio</a>
                    <a href="{{ route('catalogo', ['categoria' => 'Oficina']) }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900">Oficina</a>
                </nav>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Buscador rápido en desktop -->
                    <form action="{{ route('catalogo') }}" method="GET" class="hidden lg:block relative">
                        <input type="text" name="buscar" placeholder="Buscar muebles..." class="w-48 bg-zinc-50 focus:bg-white text-xs px-4 py-2 pr-8 rounded-full border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:border-transparent transition-all duration-300">
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
    </header>

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
                    <span class="serif-title text-xl font-bold tracking-wider text-white">SECTOR<span class="text-amber-500">MUEBLE</span></span>
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

</html>
