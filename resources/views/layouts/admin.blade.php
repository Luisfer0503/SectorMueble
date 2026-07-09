<!DOCTYPE html>
<html lang="es" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel de Administración | Sector Mueble</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

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
<body class="flex h-full min-h-screen text-zinc-800">

    <!-- Sidebar Menu -->
    <aside class="w-64 bg-zinc-900 text-white flex flex-col justify-between flex-shrink-0 border-r border-zinc-800 hidden md:flex">
        <div>
            <!-- Header Logo -->
            <div class="h-20 flex items-center px-6 border-b border-zinc-800 bg-zinc-950">
                <a href="{{ route('admin.dashboard') }}" class="serif-title text-xl font-bold tracking-wider text-white">
                    SECTOR<span class="text-amber-500">CONTROL</span>
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
        <header class="h-20 bg-white border-b border-zinc-200 flex items-center justify-between px-6 sm:px-8">
            <h2 class="text-sm font-bold uppercase tracking-widest text-zinc-500">Panel de Administración</h2>
            <div class="flex items-center space-x-4">
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
        <main class="flex-grow p-6 sm:p-8">
            @yield('contenido')
        </main>
    </div>

</body>
</html>
