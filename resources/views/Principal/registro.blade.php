@extends('layouts.app')

@section('titulo', 'Registrarse | Sector Mueble')

@section('contenido')
    <div class="min-h-[85vh] flex items-center justify-center px-4 py-8 sm:py-16">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-md border border-amber-900/10 rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
            
            <!-- Glow background accents -->
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-amber-800/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header -->
            <div class="text-center mb-6 relative z-10">
                <a href="{{ route('inicio') }}" class="inline-flex items-center justify-center space-x-2.5 mb-4 group">
                    <div class="p-1.5 bg-gradient-to-br from-amber-500/10 to-amber-800/10 rounded-2xl border border-amber-800/15 shadow-sm group-hover:scale-105 transition-all">
                        <img src="{{ asset('logo2.png') }}" alt="Isotipo Sector Mueble" class="h-8 sm:h-10 w-auto object-contain">
                    </div>
                    <img src="{{ asset('logo1.png') }}" alt="Sector Mueble Logotipo" class="h-9 sm:h-11 w-auto object-contain">
                </a>
                <h1 class="serif-title text-2xl sm:text-3xl font-bold text-zinc-950 tracking-wide">Crear Cuenta</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-1">Únete a Sector Mueble y disfruta de beneficios exclusivos</p>
            </div>

            <!-- Register Form -->
            <form action="{{ route('registro.procesar') }}" method="POST" class="space-y-4 relative z-10">
                @csrf
                
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">Nombre Completo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Tu nombre y apellido" class="w-full bg-zinc-50/80 focus:bg-white text-base sm:text-sm pl-11 pr-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 transition-all shadow-inner">
                    </div>
                    @error('name')
                        <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="tu@correo.com" class="w-full bg-zinc-50/80 focus:bg-white text-base sm:text-sm pl-11 pr-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 transition-all shadow-inner">
                    </div>
                    @error('email')
                        <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Código Postal -->
                <div>
                    <label for="reg_codigo_postal" class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">
                        Código Postal <span class="text-zinc-400 text-[10px] font-normal normal-case">(para envíos)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="codigo_postal" 
                               id="reg_codigo_postal" 
                               maxlength="5" 
                               inputmode="numeric" 
                               pattern="[0-9]{5}" 
                               value="{{ old('codigo_postal', session('codigo_postal', '')) }}" 
                               placeholder="Ej. 72760" 
                               class="w-full bg-zinc-50/80 focus:bg-white text-base sm:text-sm pl-11 pr-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 transition-all shadow-inner">
                    </div>
                    @error('codigo_postal')
                        <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const inputCP = document.getElementById('reg_codigo_postal');
                        if (inputCP && !inputCP.value) {
                            const cpLocal = localStorage.getItem('sm_codigo_postal');
                            if (cpLocal) {
                                inputCP.value = cpLocal;
                            }
                        }
                    });
                </script>


                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="Al menos 8 caracteres" class="w-full bg-zinc-50/80 focus:bg-white text-base sm:text-sm pl-11 pr-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 transition-all shadow-inner">
                    </div>
                    @error('password')
                        <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">Confirmar Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Repite tu contraseña" class="w-full bg-zinc-50/80 focus:bg-white text-base sm:text-sm pl-11 pr-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-amber-700/40 focus:border-amber-700 transition-all shadow-inner">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-amber-800 via-amber-800 to-amber-950 hover:from-amber-700 hover:to-amber-900 text-white font-bold text-xs sm:text-sm uppercase tracking-wider py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 active:scale-[0.98] mt-2">
                    Crear mi Cuenta
                </button>
            </form>

            <!-- Links -->
            <div class="mt-6 pt-5 border-t border-zinc-150 text-center text-xs text-zinc-500 relative z-10">
                <span>¿Ya tienes una cuenta registrada?</span>
                <a href="{{ route('login') }}" class="font-bold text-amber-800 hover:text-amber-700 underline ml-1.5">Inicia sesión</a>
            </div>
        </div>
    </div>
@endsection
