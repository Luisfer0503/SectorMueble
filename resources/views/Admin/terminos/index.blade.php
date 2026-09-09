@extends('layouts.admin')

@section('contenido')
<div class="px-6 sm:px-8 py-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Header de Administración -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200 pb-5">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Marco Legal & Cumplimiento</span>
            <h1 class="serif-title text-3xl font-bold text-stone-950 mt-1">Términos y Condiciones de Venta</h1>
            <p class="text-stone-600 text-sm mt-1">Edita el contrato oficial de Términos y Condiciones que se despliega en el sitio web y en la confirmación de compra.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('terminos') }}" target="_blank" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-bold rounded-xl transition-all border border-stone-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Ver Vista Pública</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-emerald-900 text-sm font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 text-xs font-bold">✕</button>
        </div>
    @endif

    <!-- Formulario Editor de Términos -->
    <form action="{{ route('admin.terminos.actualizar') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-stone-900 text-white flex items-center justify-between border-b border-stone-800">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-bold text-sm">Editor de Contrato de Términos y Condiciones</span>
                </div>
                <span class="text-xs font-mono text-stone-400" id="char-count-label">Cargando...</span>
            </div>

            <div class="p-6">
                <label for="contenido" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                    Texto Completo del Contrato <span class="text-rose-600">*</span>
                </label>
                <textarea id="contenido" 
                          name="contenido" 
                          rows="24" 
                          required 
                          oninput="updateCharCount()"
                          class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-xs sm:text-sm text-stone-900 font-mono leading-relaxed focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all shadow-inner">{{ old('contenido', $contenido) }}</textarea>
            </div>

            <div class="px-6 py-4 bg-stone-50 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-stone-500 font-medium">
                    💡 Los cambios aplicados serán visibles inmediatamente en el sitio web y en el checkout.
                </p>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span>Guardar Términos y Condiciones</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    function updateCharCount() {
        const val = document.getElementById('contenido')?.value || '';
        const charLabel = document.getElementById('char-count-label');
        if (charLabel) {
            charLabel.innerText = val.length.toLocaleString() + ' caracteres | ' + val.split('\n').length.toLocaleString() + ' líneas';
        }
    }
    document.addEventListener('DOMContentLoaded', updateCharCount);
</script>
@endsection
