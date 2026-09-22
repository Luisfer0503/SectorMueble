@extends('layouts.app')

@section('titulo', 'Políticas de Envío | Sector Mueble')

@section('contenido')
<div class="bg-[#FAF8F5] py-10 sm:py-16 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-zinc-500 mb-6">
            <a href="{{ route('inicio') }}" class="hover:text-amber-900 transition-colors">Inicio</a>
            <span>/</span>
            <span class="text-zinc-800">Políticas de Envío</span>
        </nav>

        <!-- Contenedor Principal Completo a Pantalla Completa (100% Ancho) -->
        <main class="w-full space-y-6">
            
            <!-- Encabezado Principal de Políticas de Envío -->
            <div class="bg-white border border-zinc-200/90 rounded-3xl p-6 sm:p-10 shadow-lg relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#88674B] via-[#74563C] to-[#88432A]"></div>
                <div class="border-b border-zinc-150 pb-6 mb-6">
                    <span class="inline-block bg-[#FAF3E0] text-[#74563C] border border-[#E6D7C3] text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-2">
                        Logística & Entregas a Domicilio
                    </span>
                    <h1 class="serif-title text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-950 tracking-tight mt-1">
                        Políticas de Envío
                    </h1>
                </div>
                <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed font-normal">
                    Conoce la cobertura, plazos de entrega, condiciones de flete y recomendaciones para la recepción de tus muebles de <strong class="text-zinc-900">SECTOR MUEBLE</strong> en toda la República Mexicana.
                </p>
            </div>

            <!-- Despliegue de Secciones de Políticas de Envío -->
            @foreach($secciones as $idx => $sec)
                <section id="seccion-{{ $idx + 1 }}" class="bg-white border border-zinc-200/90 rounded-3xl p-6 sm:p-10 shadow-md hover:shadow-xl transition-all duration-300">
                    
                    <!-- Encabezado de la Sección con Colores de Marca -->
                    <div class="flex items-center space-x-3 pb-4 border-b border-zinc-150 mb-5">
                        <span class="w-8 h-8 rounded-2xl bg-[#88674B] text-white text-xs font-extrabold flex items-center justify-center shrink-0 shadow-md">
                            {{ $idx + 1 }}
                        </span>
                        <h2 class="serif-title text-xl sm:text-2xl font-bold text-zinc-950 leading-snug">
                            {{ $sec['titulo'] ?? 'Sección ' . ($idx + 1) }}
                        </h2>
                    </div>

                    <!-- Cuerpo de la Sección (Puro Texto) -->
                    <div class="space-y-3 text-xs sm:text-sm text-zinc-700 leading-relaxed font-normal">
                        @foreach(explode("\n", $sec['contenido'] ?? '') as $linea)
                            @php
                                $trimmed = trim($linea);
                            @endphp
                            @if(!empty($trimmed))
                                <p class="text-zinc-700 leading-relaxed">{{ $trimmed }}</p>
                            @endif
                        @endforeach
                    </div>

                </section>
            @endforeach

        </main>
    </div>
</div>
@endsection
