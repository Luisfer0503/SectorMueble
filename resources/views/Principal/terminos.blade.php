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

        <!-- Contenedor Principal en Grid Amplio (4 Columnas) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <!-- ====== NAVEGACIÓN DE ÍNDICE LATERAL (STICKY DESKTOP) ====== -->
            <aside class="lg:col-span-1 lg:sticky lg:top-24 space-y-4">
                <div class="bg-white border border-zinc-200/90 rounded-3xl p-5 shadow-sm">
                    <div class="flex items-center space-x-2 pb-3 border-b border-zinc-150 mb-3">
                        <svg class="w-4 h-4 text-[#88674B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-zinc-900">Índice de Secciones</h3>
                    </div>

                    <nav class="space-y-1 max-h-[calc(100vh-220px)] overflow-y-auto pr-1 scrollbar-thin">
                        @foreach($secciones as $idx => $sec)
                            <a href="#seccion-{{ $idx + 1 }}" 
                               class="indice-link group flex items-start space-x-2.5 p-2 rounded-xl text-xs font-medium text-zinc-600 hover:text-[#88674B] hover:bg-[#FAF3E0]/70 transition-all duration-200">
                                <span class="w-5 h-5 rounded-full bg-zinc-100 group-hover:bg-[#88674B] text-zinc-600 group-hover:text-white text-[10px] font-extrabold flex items-center justify-center shrink-0 transition-colors">
                                    {{ $idx + 1 }}
                                </span>
                                <span class="leading-tight line-clamp-2">{{ $sec['titulo'] ?? 'Sección ' . ($idx + 1) }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Tarjeta de Soporte / WhatsApp -->
                <div class="bg-gradient-to-br from-zinc-950 via-zinc-900 to-[#582818] p-5 rounded-3xl text-white shadow-lg border border-amber-900/30">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#FAF3E0] bg-white/10 px-2.5 py-1 rounded-full border border-white/15">Soporte Directo</span>
                    <h4 class="text-sm font-bold mt-2">¿Tienes alguna duda legal o de compra?</h4>
                    <p class="text-xs text-zinc-300 mt-1 leading-relaxed">Nuestro equipo de atención está disponible para apoyarte.</p>
                    <a href="https://wa.me/5212226702641?text=Hola,%20tengo%20una%20duda%20sobre%20los%20Términos%20y%20Condiciones" 
                       target="_blank" 
                       class="mt-4 inline-flex items-center space-x-2 bg-[#FAF3E0] hover:bg-white text-[#88432A] text-xs font-extrabold px-4 py-2.5 rounded-xl shadow transition-all transform hover:scale-102">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>WhatsApp 222 670 2641</span>
                    </a>
                </div>
            </aside>

            <!-- ====== CONTENIDO PRINCIPAL POR SECCIONES (COL 3/4 AMPLIO) ====== -->
            <main class="lg:col-span-3 space-y-6">
                
                <!-- Encabezado Principal del Contrato -->
                <div class="bg-white border border-zinc-200/90 rounded-3xl p-6 sm:p-10 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#88674B] via-[#74563C] to-[#88432A]"></div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-150 pb-6 mb-6">
                        <div>
                            <span class="inline-block bg-[#FAF3E0] text-[#74563C] border border-[#E6D7C3] text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-2">
                                Marco Jurídico & Términos de Venta
                            </span>
                            <h1 class="serif-title text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-950 tracking-tight">
                                Términos y Condiciones
                            </h1>
                        </div>
                        <div class="text-left sm:text-right shrink-0">
                            <span class="text-xs text-zinc-400 font-semibold block">Última actualización:</span>
                            <span class="text-xs font-bold text-zinc-800 bg-zinc-100 px-3 py-1 rounded-lg border border-zinc-200 inline-block mt-1">
                                {{ date('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed font-normal">
                        Contrato oficial regulado de conformidad con la Ley Federal de Protección al Consumidor y la NMX-COE-001-SCFI-2018 para <strong class="text-zinc-900">CASA TAPICERÍA Y ATELIER S.A. de C.V. (SECTOR MUEBLE)</strong>.
                    </p>
                </div>

                <!-- Despliegue de Secciones de Términos y Condiciones -->
                @foreach($secciones as $idx => $sec)
                    <section id="seccion-{{ $idx + 1 }}" class="scroll-mt-28 bg-white border border-zinc-200/90 rounded-3xl p-6 sm:p-9 shadow-md hover:shadow-xl transition-all duration-300">
                        
                        <!-- Encabezado de la Sección con Colores de Marca -->
                        <div class="flex items-start space-x-3 pb-4 border-b border-zinc-150 mb-5">
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
                                    <div class="flex items-start space-x-3 bg-[#FAF8F4] p-3.5 rounded-2xl border border-stone-200/80 my-2 hover:border-[#88674B]/40 transition-colors">
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
</div>

<script>
    // Resaltado dinámico del índice según la sección visible
    document.addEventListener('DOMContentLoaded', function () {
        const sections = document.querySelectorAll('section[id^="seccion-"]');
        const navLinks = document.querySelectorAll('.indice-link');

        function onScroll() {
            let current = '';
            sections.forEach(sec => {
                const sectionTop = sec.offsetTop - 150;
                if (window.scrollY >= sectionTop) {
                    current = sec.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('bg-[#FAF3E0]', 'text-[#74563C]', 'font-bold');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('bg-[#FAF3E0]', 'text-[#74563C]', 'font-bold');
                }
            });
        }

        window.addEventListener('scroll', onScroll, { passive: true });
    });
</script>
@endsection
