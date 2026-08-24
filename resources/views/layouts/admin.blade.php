<!DOCTYPE html>
<html lang="es" class="h-full bg-[#FAF3E0]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel de Administración | Sector Mueble</title>
    
    <!-- Google Fonts CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Styles and Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #0B0A0A;
        }
        .serif-title, h1, h2, h3, .font-heading {
            font-family: 'Playfair Display', 'Poppins', Georgia, serif;
        }
    </style>
</head>
<body class="flex h-full min-h-screen text-zinc-800">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="mobileAdminBackdrop" onclick="cerrarSidebarMovil()" class="fixed inset-0 z-40 bg-zinc-950/70 backdrop-blur-sm hidden md:hidden transition-opacity"></div>

    <!-- Mobile Sidebar Drawer (Menu Hamburguesa) -->
    <aside id="mobileAdminSidebar" class="fixed top-0 left-0 bottom-0 z-50 w-72 bg-zinc-900 text-white flex flex-col justify-between border-r border-zinc-800 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
        <div>
            <!-- Header Logo Movil -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-zinc-800 bg-zinc-950">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('logo2.png') }}" alt="Logo 2" class="h-8 w-auto object-contain brightness-0 invert">
                    <img src="{{ asset('logo1.png') }}" alt="Logo 1" class="h-10 w-auto object-contain brightness-0 invert">
                    <span class="serif-title text-sm font-bold tracking-wider text-white">SECTOR<span class="text-amber-500">CONTROL</span></span>
                </a>
                <button type="button" onclick="cerrarSidebarMovil()" class="text-zinc-400 hover:text-white p-1 rounded-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links Movil -->
            <nav class="mt-4 px-4 space-y-1 overflow-y-auto max-h-[calc(100vh-160px)]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.dashboard') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.productos') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.productos') || Route::is('admin.productos.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Muebles (Catálogo)
                </a>

                <a href="{{ route('admin.cupones') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.cupones') || Route::is('admin.cupones.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                    Cupones Descuento
                </a>

                <a href="{{ route('admin.ruleta') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.ruleta') || Route::is('admin.ruleta.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Ruleta de Premios
                </a>

                <a href="{{ route('admin.zapatos') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.zapatos') || Route::is('admin.zapatos.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Inventario Zapatos (Escáner IA)
                </a>

                <a href="{{ route('admin.pedidos') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.pedidos') || Route::is('admin.pedidos.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Pedidos
                </a>

                <div class="pt-4 mt-4 border-t border-zinc-800">
                    <a href="{{ route('inicio') }}" class="flex items-center px-4 py-3 text-xs font-semibold text-zinc-400 hover:text-white rounded uppercase tracking-wider transition-colors">
                        <svg class="mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a la Tienda
                    </a>
                </div>
            </nav>
        </div>

        <!-- Bottom Session Info Movil -->
        <div class="p-4 border-t border-zinc-800 bg-zinc-950 flex items-center justify-between">
            <div class="truncate pr-2">
                <span class="block text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</span>
                <span class="block text-[10px] text-zinc-400 truncate">{{ auth()->user()->email }}</span>
            </div>
            <a href="{{ route('logout') }}" class="text-zinc-400 hover:text-rose-500 p-2 rounded transition-colors" title="Cerrar Sesión">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </aside>

    <!-- Desktop Sidebar Menu -->
    <aside class="w-64 bg-zinc-900 text-white flex flex-col justify-between flex-shrink-0 border-r border-zinc-800 hidden md:flex">
        <div>
            <!-- Header Logo -->
            <div class="h-24 flex items-center px-6 border-b border-zinc-800 bg-zinc-950">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('logo2.png') }}" alt="Logo 2" class="h-10 w-auto object-contain brightness-0 invert">
                    <img src="{{ asset('logo1.png') }}" alt="Logo 1" class="h-12 w-auto object-contain brightness-0 invert">
                    <span class="serif-title text-base font-bold tracking-wider text-white">SECTOR<span class="text-amber-500">CONTROL</span></span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 px-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.dashboard') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.productos') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.productos') || Route::is('admin.productos.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Muebles (Catálogo)
                </a>

                <a href="{{ route('admin.cupones') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.cupones') || Route::is('admin.cupones.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                    Cupones Descuento
                </a>

                <a href="{{ route('admin.ruleta') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.ruleta') || Route::is('admin.ruleta.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Ruleta de Premios
                </a>

                <a href="{{ route('admin.zapatos') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.zapatos') || Route::is('admin.zapatos.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Inventario Zapatos (Escáner IA)
                </a>

                <a href="{{ route('admin.pedidos') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded transition-colors {{ Route::is('admin.pedidos') || Route::is('admin.pedidos.*') ? 'bg-amber-800 text-white' : 'text-zinc-400 hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Pedidos
                </a>

                <div class="pt-4 mt-4 border-t border-zinc-800">
                    <a href="{{ route('inicio') }}" class="flex items-center px-4 py-3 text-xs font-semibold text-zinc-400 hover:text-white rounded uppercase tracking-wider transition-colors">
                        <svg class="mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a la Tienda
                    </a>
                </div>
            </nav>
        </div>

        <!-- Bottom Session Info -->
        <div class="p-4 border-t border-zinc-800 bg-zinc-950 flex items-center justify-between">
            <div class="truncate pr-2">
                <span class="block text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</span>
                <span class="block text-[10px] text-zinc-400 truncate">{{ auth()->user()->email }}</span>
            </div>
            <a href="{{ route('logout') }}" class="text-zinc-400 hover:text-rose-500 p-2 rounded transition-colors" title="Cerrar Sesión">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0 overflow-y-auto">
        <!-- Top navbar in main content -->
        <header class="h-20 bg-white border-b border-zinc-200 flex items-center justify-between px-4 sm:px-8">
            <div class="flex items-center space-x-3">
                <!-- Boton Menu Hamburguesa Movil -->
                <button type="button" onclick="abrirSidebarMovil()" class="p-2 rounded-lg text-zinc-700 hover:text-zinc-900 hover:bg-zinc-100 md:hidden" title="Abrir Menú">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 md:hidden">
                    <img src="{{ asset('logo2.png') }}" alt="Logo 2" class="h-8 w-auto object-contain">
                    <span class="serif-title text-sm font-bold text-zinc-900">SECTOR<span class="text-amber-600">ADMIN</span></span>
                </a>
                <h2 class="text-sm font-bold uppercase tracking-widest text-zinc-500 hidden md:block">Panel de Administración</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inicio') }}" target="_blank" class="text-xs bg-zinc-100 hover:bg-zinc-200 text-zinc-700 px-3 py-2 rounded font-semibold transition-all">
                    Ver Sitio Web
                </a>
            </div>
        </header>

        <!-- Flash alerts -->
        @if(session('success'))
            <div class="px-6 sm:px-8 mt-6">
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
            <div class="px-6 sm:px-8 mt-6">
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

        <!-- Main Body -->
        <main class="flex-grow p-4 sm:p-8">
            @yield('contenido')
        </main>
    </div>

    <!-- Script Menu Hamburguesa Movil -->
    <script>
        function abrirSidebarMovil() {
            const backdrop = document.getElementById('mobileAdminBackdrop');
            const sidebar = document.getElementById('mobileAdminSidebar');
            if (backdrop && sidebar) {
                backdrop.classList.remove('hidden');
                sidebar.classList.remove('-translate-x-full');
            }
        }

        function cerrarSidebarMovil() {
            const backdrop = document.getElementById('mobileAdminBackdrop');
            const sidebar = document.getElementById('mobileAdminSidebar');
            if (backdrop && sidebar) {
                backdrop.classList.add('hidden');
                sidebar.classList.add('-translate-x-full');
            }
        }
    </script>
</body>
</html>
