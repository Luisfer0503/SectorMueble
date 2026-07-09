@extends('layouts.app')

@section('titulo', 'Registrarse | Sector Mueble')

@section('contenido')
    <div class="max-w-md mx-auto px-4 py-16 sm:py-24">
        <div class="bg-white border border-zinc-200 rounded-lg p-8 shadow-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <span class="serif-title text-2xl font-bold tracking-wider text-zinc-900">SECTOR<span class="text-amber-800">MUEBLE</span></span>
                <h2 class="text-lg font-bold text-zinc-950 mt-4 uppercase tracking-wider">Crear una Cuenta</h2>
                <p class="text-xs text-zinc-500 mt-1">Regístrate para guardar tus pedidos e información de entrega</p>
            </div>

            <!-- Register Form -->
            <form action="{{ route('registro.procesar') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Nombre Completo</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('name')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

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
                    <label for="password" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Contraseña</label>
                    <input type="password" name="password" id="password" required class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    @error('password')
                        <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider py-4 rounded mt-4 transition-colors shadow">
                    Crear mi Cuenta
                </button>
            </form>

            <!-- Links -->
            <div class="mt-8 pt-6 border-t border-zinc-150 text-center text-xs text-zinc-650">
                <span>¿Ya tienes una cuenta?</span>
                <a href="{{ route('login') }}" class="font-bold text-amber-850 hover:text-amber-750 underline ml-1">Inicia sesión aquí</a>
            </div>
        </div>
    </div>
@endsection
