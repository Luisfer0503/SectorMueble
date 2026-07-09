@extends('layouts.admin')

@section('contenido')
    <!-- Header -->
    <div class="pb-6 border-b border-zinc-200 mb-8">
        <nav class="flex text-xs font-medium text-zinc-500 space-x-2 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-850">Inicio</a>
            <span>/</span>
            <a href="{{ route('admin.cupones') }}" class="hover:text-amber-850">Cupones</a>
            <span>/</span>
            <span class="text-zinc-800">Editar</span>
        </nav>
        <h1 class="serif-title text-3xl font-bold text-zinc-950">Editar Cupón de Descuento</h1>
        <p class="text-zinc-500 text-sm mt-1">Modifica el cupón promocional "{{ $cupon->codigo }}".</p>
    </div>

    <!-- Form -->
    <div class="bg-white border border-zinc-200 rounded p-6 sm:p-8 shadow-sm max-w-lg">
        <form action="{{ route('admin.cupones.actualizar', $cupon->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Codigo -->
            <div>
                <label for="codigo" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Código del Cupón</label>
                <input type="text" name="codigo" id="codigo" required value="{{ old('codigo', $cupon->codigo) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700 font-mono tracking-wider">
                @error('codigo')
                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tipo -->
            <div>
                <label for="tipo" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Tipo de Descuento</label>
                <select name="tipo" id="tipo" required class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                    <option value="porcentaje" {{ old('tipo', $cupon->tipo) === 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                    <option value="fijo" {{ old('tipo', $cupon->tipo) === 'fijo' ? 'selected' : '' }}>Descuento Fijo ($ MXN)</option>
                </select>
                @error('tipo')
                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Valor -->
            <div>
                <label for="valor" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Valor del Descuento</label>
                <input type="number" step="0.01" name="valor" id="valor" required value="{{ old('valor', $cupon->valor) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                <span class="text-[10px] text-zinc-400 mt-1 block">Ingresa 10 para un 10%, o 500 para un descuento de $500.00 MXN en pesos.</span>
                @error('valor')
                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Activo -->
            <div class="flex items-center">
                <input type="checkbox" name="activo" id="activo" {{ old('activo', $cupon->activo) ? 'checked' : '' }} value="1" class="h-4 w-4 text-amber-850 border-zinc-300 rounded focus:ring-amber-700">
                <label for="activo" class="ml-2 text-xs text-zinc-700 font-medium cursor-pointer">Mantener este cupón activo</label>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-zinc-150 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.cupones') }}" class="border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider px-6 py-3 rounded transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded shadow transition-colors">
                    Actualizar Cupón
                </button>
            </div>
        </form>
    </div>
@endsection
