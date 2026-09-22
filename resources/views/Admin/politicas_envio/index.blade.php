@extends('layouts.admin')

@section('contenido')
<div class="px-6 sm:px-8 py-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Header de Administración -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200 pb-5">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Marco Legal & Logística</span>
            <h1 class="serif-title text-3xl font-bold text-stone-950 mt-1">Políticas de Envío por Secciones</h1>
            <p class="text-stone-600 text-sm mt-1">Edita las políticas de envío dividiéndolas en secciones con títulos claros. Los cambios se actualizarán automáticamente en la tienda.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('politicas-envio') }}" target="_blank" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-amber-800 hover:bg-amber-900 text-white text-xs font-bold rounded-xl transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Ver Vista Pública en Vivo</span>
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

    <!-- Formulario Editor de Políticas de Envío por Secciones -->
    <form action="{{ route('admin.politicas_envio.actualizar') }}" method="POST" id="form-politicas-envio" class="space-y-6">
        @csrf

        <div class="flex items-center justify-between bg-stone-100 p-3 rounded-2xl border border-stone-200">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold uppercase tracking-wider text-stone-700">Modo de Edición:</span>
                <span class="bg-amber-100 text-amber-900 border border-amber-300 text-[11px] font-extrabold px-3 py-1 rounded-full">
                    📑 Por Secciones e Títulos (Recomendado)
                </span>
            </div>
            <button type="button" onclick="agregarNuevaSeccion()" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-stone-900 hover:bg-amber-800 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Agregar Nueva Sección</span>
            </button>
        </div>

        <!-- Contenedor Dinámico de Secciones -->
        <div id="secciones-container" class="space-y-6">
            @foreach($secciones as $index => $sec)
                <div class="seccion-card bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden transition-all hover:border-amber-700/40" data-index="{{ $index }}">
                    <!-- Cabecera de la Sección -->
                    <div class="px-6 py-3.5 bg-stone-900 text-white flex items-center justify-between border-b border-stone-800">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-full bg-amber-800 text-white text-xs font-extrabold flex items-center justify-center seccion-numero">
                                {{ $index + 1 }}
                            </span>
                            <span class="font-bold text-xs uppercase tracking-wider text-stone-300">Sección #<span class="num-lbl">{{ $index + 1 }}</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="moverSeccion(this, 'up')" title="Mover arriba" class="p-1.5 bg-stone-800 hover:bg-stone-700 text-stone-300 rounded-lg text-xs transition-colors">
                                ⬆️ Arriba
                            </button>
                            <button type="button" onclick="moverSeccion(this, 'down')" title="Mover abajo" class="p-1.5 bg-stone-800 hover:bg-stone-700 text-stone-300 rounded-lg text-xs transition-colors">
                                ⬇️ Abajo
                            </button>
                            <button type="button" onclick="eliminarSeccion(this)" title="Eliminar esta sección" class="p-1.5 bg-rose-900/80 hover:bg-rose-700 text-white rounded-lg text-xs transition-colors ml-2">
                                🗑️ Eliminar
                            </button>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Título de la Sección -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                Título de la Sección <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" 
                                   name="secciones[{{ $index }}][titulo]" 
                                   value="{{ old("secciones.{$index}.titulo", $sec['titulo'] ?? '') }}" 
                                   placeholder="Ej. 1. Cobertura de Entrega y Envíos Nacionales" 
                                   required 
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-sm font-bold text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">
                        </div>

                        <!-- Contenido de la Sección -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                Contenido y Puntos de la Sección <span class="text-rose-600">*</span>
                            </label>
                            <textarea name="secciones[{{ $index }}][contenido]" 
                                      rows="6" 
                                      required 
                                      placeholder="Escribe el contenido de esta sección. Puedes usar viñetas (• o -) para resaltar puntos importantes."
                                      class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-xs sm:text-sm text-stone-800 font-sans leading-relaxed focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">{{ old("secciones.{$index}.contenido", $sec['contenido'] ?? '') }}</textarea>
                            <span class="text-[11px] text-stone-400 block mt-1">💡 Usa saltos de línea para separar párrafos. Las líneas con "•" o "-" se mostrarán claramente en el sitio web.</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botón de Agregar Sección en la Parte Inferior -->
        <div class="text-center pt-2">
            <button type="button" onclick="agregarNuevaSeccion()" class="inline-flex items-center space-x-2 px-6 py-3.5 bg-stone-100 hover:bg-stone-200 border-2 border-dashed border-stone-300 text-stone-800 font-bold text-xs uppercase tracking-wider rounded-2xl transition-all shadow-xs">
                <svg class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Agregar Otra Sección a las Políticas de Envío</span>
            </button>
        </div>

        <!-- Botón de Guardar Todo -->
        <div class="sticky bottom-6 bg-white border border-stone-300 p-4 rounded-2xl shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 z-30">
            <div class="flex items-center space-x-2 text-stone-600 text-xs font-medium">
                <svg class="w-5 h-5 text-amber-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Los cambios guardados se verán reflejados al instante en el sitio público.</span>
            </div>
            <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-amber-800 hover:bg-amber-900 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center space-x-2">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                <span>Guardar Todas las Secciones</span>
            </button>
        </div>
    </form>

</div>

<script>
    let seccionCounter = {{ count($secciones) }};

    function agregarNuevaSeccion() {
        const container = document.getElementById('secciones-container');
        const index = seccionCounter++;
        const numDisplay = container.querySelectorAll('.seccion-card').length + 1;

        const card = document.createElement('div');
        card.className = 'seccion-card bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden transition-all hover:border-amber-700/40 animate-fadeIn';
        card.setAttribute('data-index', index);
        
        card.innerHTML = `
            <div class="px-6 py-3.5 bg-stone-900 text-white flex items-center justify-between border-b border-stone-800">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-amber-800 text-white text-xs font-extrabold flex items-center justify-center seccion-numero">
                        ${numDisplay}
                    </span>
                    <span class="font-bold text-xs uppercase tracking-wider text-stone-300">Sección #<span class="num-lbl">${numDisplay}</span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="moverSeccion(this, 'up')" title="Mover arriba" class="p-1.5 bg-stone-800 hover:bg-stone-700 text-stone-300 rounded-lg text-xs transition-colors">⬆️ Arriba</button>
                    <button type="button" onclick="moverSeccion(this, 'down')" title="Mover abajo" class="p-1.5 bg-stone-800 hover:bg-stone-700 text-stone-300 rounded-lg text-xs transition-colors">⬇️ Abajo</button>
                    <button type="button" onclick="eliminarSeccion(this)" title="Eliminar esta sección" class="p-1.5 bg-rose-900/80 hover:bg-rose-700 text-white rounded-lg text-xs transition-colors ml-2">🗑️ Eliminar</button>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                        Título de la Sección <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" 
                           name="secciones[${index}][titulo]" 
                           value="${numDisplay}. Nueva Sección de Políticas de Envío" 
                           placeholder="Ej. ${numDisplay}. Nueva Sección" 
                           required 
                           class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-sm font-bold text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                        Contenido y Puntos de la Sección <span class="text-rose-600">*</span>
                    </label>
                    <textarea name="secciones[${index}][contenido]" 
                              rows="6" 
                              required 
                              placeholder="Escribe el contenido de esta sección..."
                              class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-xs sm:text-sm text-stone-800 font-sans leading-relaxed focus:outline-none focus:ring-2 focus:ring-amber-700 focus:bg-white transition-all"></textarea>
                </div>
            </div>
        `;

        container.appendChild(card);
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        actualizarNumerosSecciones();
    }

    function eliminarSeccion(btn) {
        const card = btn.closest('.seccion-card');
        if (!card) return;
        const total = document.querySelectorAll('.seccion-card').length;
        if (total <= 1) {
            alert('Debes mantener al menos una sección en las políticas de envío.');
            return;
        }
        if (confirm('¿Estás seguro de que deseas eliminar esta sección?')) {
            card.remove();
            actualizarNumerosSecciones();
        }
    }

    function moverSeccion(btn, dir) {
        const card = btn.closest('.seccion-card');
        if (!card) return;
        if (dir === 'up' && card.previousElementSibling) {
            card.parentNode.insertBefore(card, card.previousElementSibling);
        } else if (dir === 'down' && card.nextElementSibling) {
            card.parentNode.insertBefore(card.nextElementSibling, card);
        }
        actualizarNumerosSecciones();
    }

    function actualizarNumerosSecciones() {
        const cards = document.querySelectorAll('.seccion-card');
        cards.forEach((card, idx) => {
            const num = idx + 1;
            const numSpan = card.querySelector('.seccion-numero');
            const numLbl = card.querySelector('.num-lbl');
            if (numSpan) numSpan.innerText = num;
            if (numLbl) numLbl.innerText = num;
        });
    }
</script>
@endsection
