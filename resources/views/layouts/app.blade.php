<!DOCTYPE html>
<html lang="es" class="h-full bg-[#FAF9F6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                    <a href="{{ route('carrito') }}" class="relative p-2 text-zinc-600 hover:text-amber-800 transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @php
                            $cantidadCarrito = array_sum(array_column(session('carrito', []), 'cantidad'));
                        @endphp
                        @if($cantidadCarrito > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/3 -translate-y-1/3 bg-amber-800 rounded-full">
                                {{ $cantidadCarrito }}
                            </span>
                        @endif
                    </a>
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
</html>
