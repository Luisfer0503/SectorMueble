@extends('layouts.app')

@section('titulo', 'Términos y Condiciones | Sector Mueble')

@section('contenido')
<div class="bg-[#FAF8F5] py-10 sm:py-16 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-zinc-500 mb-6">
            <a href="{{ route('inicio') }}" class="hover:text-amber-900 transition-colors">Inicio</a>
            <span>/</span>
            <span class="text-zinc-800">Términos y Condiciones</span>
        </nav>

        <!-- Contenedor Principal Completo a Pantalla Completa (100% Ancho) -->
        <main class="w-full space-y-6">
            
            <!-- Encabezado Principal del Contrato -->
            <div class="bg-white border border-zinc-200/90 rounded-3xl p-6 sm:p-10 shadow-lg relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#88674B] via-[#74563C] to-[#88432A]"></div>
                <div class="border-b border-zinc-150 pb-6 mb-6">
                    <span class="inline-block bg-[#FAF3E0] text-[#74563C] border border-[#E6D7C3] text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-2">
                        Marco Jurídico & Términos de Venta
                    </span>
                    <h1 class="serif-title text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-950 tracking-tight mt-1">
                        Términos y Condiciones
                    </h1>
                </div>
                <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed font-normal">
                    Contrato oficial regulado de conformidad con la Ley Federal de Protección al Consumidor y la NMX-COE-001-SCFI-2018 para <strong class="text-zinc-900">CASA TAPICERÍA Y ATELIER S.A. de C.V. (SECTOR MUEBLE)</strong>.
                </p>
            </div>

            <!-- Despliegue de Secciones de Términos y Condiciones a Pantalla Completa -->
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

                    <!-- Cuerpo de la Sección con Formateo Especial de Viñetas -->
                    <div class="space-y-3 text-xs sm:text-sm text-zinc-700 leading-relaxed font-normal">
                        @php
                            $lineas = explode("\n", $sec['contenido'] ?? '');
                        @endphp

                        @foreach($lineas as $linea)
                            @php
                                $trimmed = trim($linea);
                            @endphp

                            @if(empty($trimmed))
                                <div class="h-2"></div>
                            @elseif(str_starts_with($trimmed, '•') || str_starts_with($trimmed, '-') || str_starts_with($trimmed, '✓'))
                                @php
                                    $textoPunto = ltrim($trimmed, '•-✓ ');
                                @endphp
                                <div class="flex items-start space-x-3 bg-[#FAF8F4] p-3.5 sm:p-4 rounded-2xl border border-stone-200/80 my-2 hover:border-[#88674B]/40 transition-colors">
                                    <div class="w-5 h-5 rounded-full bg-[#FAF3E0] border border-[#E6D7C3] text-[#74563C] flex items-center justify-center shrink-0 mt-0.5 shadow-2xs font-extrabold text-xs">
                                        ✓
                                    </div>
                                    <div class="text-xs sm:text-sm text-zinc-800 font-medium">
                                        {!! nl2br(e($textoPunto)) !!}
                                    </div>
                                </div>
                            @else
                                <p class="text-zinc-700 leading-relaxed">
                                    {!! nl2br(e($trimmed)) !!}
                                </p>
                            @endif
                        @endforeach
                    </div>

                </section>
            @endforeach

        </main>
    </div>
</div>
@endsection
