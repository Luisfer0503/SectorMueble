@extends('layouts.app')

@section('titulo', 'Términos y Condiciones | Sector Mueble')

@section('contenido')
<div class="bg-[#FAF8F5] py-12 sm:py-16 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-zinc-500 mb-6">
            <a href="{{ route('inicio') }}" class="hover:text-amber-900 transition-colors">Inicio</a>
            <span>/</span>
            <span class="text-zinc-800">Términos y Condiciones</span>
        </nav>

        <div class="bg-white border border-zinc-200/90 rounded-3xl shadow-xl p-6 sm:p-10 md:p-12">
            <!-- Header con sello elegante -->
            <div class="border-b border-zinc-200 pb-8 mb-8 text-center sm:text-left">
                <span class="inline-block bg-amber-50 text-amber-900 border border-amber-200 text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-3">
                    Aviso Legal & Marco Jurídico
                </span>
                <h1 class="serif-title text-3xl sm:text-5xl font-extrabold text-zinc-950 tracking-tight">
                    Términos y Condiciones
                </h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-2 font-medium">
                    Última actualización: {{ date('d/m/Y') }} — CASA TAPICERÍA Y ATELIER S.A. de C.V. (SECTOR MUEBLE)
                </p>
            </div>

            <!-- Contenido del Documento Legal -->
            <div class="prose prose-amber max-w-none text-zinc-700 text-sm sm:text-base leading-relaxed space-y-6">
                @foreach(explode("\n\n", $contenido) as $paragraph)
                    @php
                        $trimmed = trim($paragraph);
                    @endphp
                    @if(!empty($trimmed))
                        @if(strlen($trimmed) < 80 && !str_contains($trimmed, '.') && !str_contains($trimmed, ','))
                            <h3 class="serif-title text-xl sm:text-2xl font-bold text-zinc-900 mt-8 mb-3 border-l-4 border-[#88674B] pl-3.5 pt-0.5">
                                {{ $trimmed }}
                            </h3>
                        @else
                            <p class="text-zinc-700 leading-relaxed font-normal whitespace-pre-line">
                                {{ $trimmed }}
                            </p>
                        @endif
                    @endif
                @endforeach
            </div>

            <!-- Footer Informativo -->
            <div class="mt-12 pt-8 border-t border-zinc-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500">
                <p>© {{ date('Y') }} SECTOR MUEBLE. Todos los derechos reservados.</p>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('inicio') }}" class="font-bold text-[#88674B] hover:underline">Volver a la Tienda</a>
                    <a href="mailto:hola@sectormueble.com.mx" class="font-bold text-zinc-700 hover:underline">hola@sectormueble.com.mx</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
