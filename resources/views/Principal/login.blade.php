@extends('layouts.app')

@section('titulo', 'Iniciar Sesión | Sector Mueble')

@section('contenido')
    <div class="max-w-md mx-auto px-4 py-16 sm:py-24">
        <div class="bg-white border border-zinc-200 rounded-lg p-8 shadow-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <span class="serif-title text-2xl font-bold tracking-wider text-zinc-900">SECTOR<span class="text-amber-800">MUEBLE</span></span>
                <h2 class="text-lg font-bold text-zinc-950 mt-4 uppercase tracking-wider">Iniciar Sesión</h2>
                <p class="text-xs text-zinc-500 mt-1">Accede para continuar con tu proceso de compra</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.procesar') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('email')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider">Contraseña</label>
                    </div>
                    <input type="password" name="password" id="password" required class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('password')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-amber-850 border-zinc-300 rounded focus:ring-amber-700">
                    <label for="remember" class="ml-2 text-xs text-zinc-600 font-medium cursor-pointer">Recuérdame en este dispositivo</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider py-4 rounded transition-colors shadow">
                    Entrar a mi Cuenta
                </button>
            </form>

            <!-- Links -->
            <div class="mt-8 pt-6 border-t border-zinc-150 text-center text-xs text-zinc-650">
                <span>¿No tienes una cuenta aún?</span>
                <a href="{{ route('registro') }}" class="font-bold text-amber-850 hover:text-amber-750 underline ml-1">Regístrate aquí</a>
            </div>
        </div>
    </div>
@endsection
