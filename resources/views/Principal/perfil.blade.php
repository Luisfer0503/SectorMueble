@extends('layouts.app')

@section('titulo', 'Mi Perfil | Sector Mueble')

@section('contenido')
<div class="bg-[#FAF8F5] min-h-screen py-10 sm:py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header del Perfil -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-zinc-200/80 mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#88674B] text-white flex items-center justify-center font-extrabold text-2xl sm:text-3xl shadow-md border-2 border-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="serif-title text-xl sm:text-2xl font-bold text-zinc-900">{{ $user->name }}</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 font-medium">{{ $user->email }}</p>
                    <div class="mt-1.5 flex items-center space-x-2">
                        @if($user->hasVerifiedEmail())
                            <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Correo Verificado</span>
                            </span>
                        @else
                            <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                <span>Verificación Pendiente</span>
                            </span>
                        @endif
                        @if($user->is_admin)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#88674B] text-white uppercase tracking-wider">
                                Admin
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('logout') }}" class="w-full sm:w-auto text-center px-4 py-2.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl text-xs font-bold transition-colors border border-rose-200/80">
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Formulario de Edición de Datos -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-zinc-200/80">
            <div class="border-b border-zinc-100 pb-4 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="serif-title text-lg sm:text-xl font-bold text-zinc-900">Información Personal</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Actualiza tus datos de contacto y entrega en Sector Mueble.</p>
                </div>
            </div>

            @if(session('exito'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('exito') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold space-y-1">
                    @foreach($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('perfil.actualizar') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Campo Correo (NO EDITABLE) -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span>Correo Electrónico</span>
                        <span class="text-[10px] text-zinc-400 font-normal normal-case flex items-center space-x-1">
                            <svg class="w-3 h-3 text-zinc-400 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>No se puede modificar</span>
                        </span>
                    </label>
                    <div class="relative">
                        <input type="email" value="{{ $user->email }}" readonly disabled class="w-full bg-zinc-100/80 text-zinc-500 text-sm font-medium px-4 py-3 rounded-2xl border border-zinc-200 cursor-not-allowed select-none">
                        <div class="absolute right-3.5 top-3.5 text-zinc-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Campo Nombre -->
                <div>
                    <label for="name" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Nombre Completo *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full bg-zinc-50 focus:bg-white text-zinc-900 text-sm font-medium px-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50 focus:border-[#88674B] transition-all">
                </div>

                <!-- Grid Teléfono y CP -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="telefono" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Teléfono / WhatsApp</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $user->telefono) }}" placeholder="Ej. 2221234567" class="w-full bg-zinc-50 focus:bg-white text-zinc-900 text-sm font-medium px-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50 focus:border-[#88674B] transition-all">
                    </div>
                    <div>
                        <label for="codigo_postal" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Código Postal de Envío</label>
                        <input type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal', $user->codigo_postal) }}" placeholder="Ej. 72670" class="w-full bg-zinc-50 focus:bg-white text-zinc-900 text-sm font-medium px-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50 focus:border-[#88674B] transition-all">
                    </div>
                </div>

                <!-- Sección Cambiar Contraseña (Opcional) -->
                <div class="border-t border-zinc-100 pt-6">
                    <h3 class="text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Cambiar Contraseña (Opcional)</h3>
                    <p class="text-xs text-zinc-400 mb-4">Deja estos campos vacíos si deseas conservar tu contraseña actual.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-zinc-600 mb-1">Nueva Contraseña</label>
                            <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres" class="w-full bg-zinc-50 focus:bg-white text-zinc-900 text-sm px-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50 focus:border-[#88674B] transition-all">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-zinc-600 mb-1">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite la contraseña" class="w-full bg-zinc-50 focus:bg-white text-zinc-900 text-sm px-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50 focus:border-[#88674B] transition-all">
                        </div>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="pt-4 flex items-center justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-[#4c6f4f] hover:bg-[#3c583e] text-white text-xs sm:text-sm font-bold uppercase tracking-wider px-8 py-3.5 rounded-2xl shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Guardar Cambios</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
