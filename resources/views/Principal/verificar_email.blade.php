@extends('layouts.app')

@section('titulo', 'Verifica tu Correo Electrónico | Sector Mueble')

@section('contenido')
<div class="min-h-[70vh] bg-white py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-amber-900/10 text-center">
        
        <!-- Icono animado de correo enviado -->
        <div class="mx-auto w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center text-amber-800 shadow-inner">
            <svg class="w-10 h-10 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-amber-950 serif-title">
                ¡Confirma tu Correo Electrónico!
            </h1>
            <p class="mt-3 text-sm text-stone-600 leading-relaxed">
                Hemos enviado un correo de verificación a:
            </p>
            <p class="mt-1 text-base font-bold text-amber-900 bg-amber-50 py-2 px-4 rounded-lg inline-block border border-amber-200/60 font-mono">
                {{ auth()->user()->email }}
            </p>
            <p class="mt-4 text-xs text-stone-500 leading-relaxed">
                Por favor, revisa tu bandeja de entrada (o carpeta de correo no deseado/spam) y haz clic en el enlace de confirmación para validar tu cuenta en <strong>Sector Mueble</strong>.
            </p>
        </div>

        <!-- Alertas de estado de sesión -->
        @if (session('status') == 'verification-link-sent')
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-md text-emerald-800 text-xs font-semibold text-left flex items-start space-x-2">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>¡Se ha enviado un nuevo enlace de verificación a tu correo electrónico!</span>
            </div>
        @endif

        @if (session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-md text-emerald-800 text-xs font-semibold text-left">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-md text-rose-800 text-xs font-semibold text-left">
                {{ session('error') }}
            </div>
        @endif

        <!-- Botón para Reenviar Correo -->
        <div class="pt-4 border-t border-stone-100 space-y-4">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-amber-800 hover:bg-amber-900 text-white font-semibold rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reenviar Correo de Verificación</span>
                </button>
            </form>

            <div class="flex items-center justify-between text-xs text-stone-500 pt-2">
                <a href="{{ route('inicio') }}" class="hover:text-amber-800 underline">
                    Ir al Inicio
                </a>
                <form method="POST" action="{{ route('logout.post') }}">
                    @csrf
                    <button type="submit" class="hover:text-rose-700 underline font-medium">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
