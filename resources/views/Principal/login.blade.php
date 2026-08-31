@extends('layouts.app')

@section('titulo', 'Iniciar Sesión | Sector Mueble')

@section('contenido')
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-8 sm:py-16">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-md border border-amber-900/10 rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
            
            <!-- Glow background accents -->
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-amber-800/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <a href="{{ route('inicio') }}" class="inline-flex items-center justify-center space-x-2.5 mb-4 group">
                    <div class="p-1.5 bg-gradient-to-br from-amber-500/10 to-amber-800/10 rounded-2xl border border-amber-800/15 shadow-sm group-hover:scale-105 transition-all">
                        <img src="{{ asset('logo2.png') }}" alt="Isotipo Sector Mueble" class="h-8 sm:h-10 w-auto object-contain" style="max-height: 40px; max-width: 120px;">
                    </div>
                    <img src="{{ asset('logo1.png') }}" alt="Sector Mueble Logotipo" class="h-9 sm:h-11 w-auto object-contain" style="max-height: 48px; max-width: 220px;">
                </a>
                <h1 class="serif-title text-2xl sm:text-3xl font-bold text-zinc-950 tracking-wide">¡Hola de nuevo!</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-1">Ingresa a tu cuenta para gestionar tus compras y favoritos</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.procesar') }}" method="POST" class="space-y-5 relative z-10">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1.5">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="tu@correo.com" class="w-full bg-zinc-50/80 focus:bg-white text-base sm:text-sm pl-11 pr-4 py-3.5 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 transition-all shadow-inner">
                    </div>
                    @error('email')
                        <span class="text-xs text-rose-600 font-semibold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-xs font-bold text-zinc-600 uppercase tracking-wider">Contraseña</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-zinc-50/80 focus:bg-white text-base sm:text-sm pl-11 pr-11 py-3.5 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 transition-all shadow-inner">
                        <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-xs text-rose-600 font-semibold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center pt-1">
                    <input type="checkbox" name="remember" id="remember" class="h-4.5 w-4.5 text-amber-800 border-zinc-300 rounded-lg focus:ring-amber-700 cursor-pointer">
                    <label for="remember" class="ml-2.5 text-xs text-zinc-600 font-medium cursor-pointer select-none">Mantener sesión iniciada</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-amber-800 via-amber-800 to-amber-950 hover:from-amber-700 hover:to-amber-900 text-white font-bold text-xs sm:text-sm uppercase tracking-wider py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 active:scale-[0.98]">
                    Entrar a mi Cuenta
                </button>
            </form>

            <!-- Links -->
            <div class="mt-8 pt-6 border-t border-zinc-150 text-center text-xs text-zinc-500 relative z-10">
                <span>¿No tienes una cuenta aún?</span>
                <a href="{{ route('registro') }}" class="font-bold text-amber-800 hover:text-amber-700 underline ml-1.5">Regístrate gratis</a>
            </div>
        </div>
    </div>
@endsection
