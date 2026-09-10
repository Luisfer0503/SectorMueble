@extends('layouts.app')

@section('titulo', 'Facturación Electrónica SAT | Sector Mueble')

@section('contenido')
    <div class="bg-[#FAF8F5] min-h-screen py-10 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Sección -->
            <div class="text-center mb-10">
                <span class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-[#4c6f4f] text-white text-xs font-bold uppercase tracking-widest shadow-xs mb-3">
                    <span>⚡ Emisión Inmediata SAT CFDI 4.0</span>
                </span>
                <h1 class="serif-title text-3xl sm:text-4xl font-bold text-zinc-950">Portal de Facturación Electrónica</h1>
                <p class="mt-2 text-zinc-600 text-sm max-w-xl mx-auto">
                    Consulta tu pedido, genera tu comprobante fiscal digital o descarga tus archivos PDF y XML timbrados.
                </p>
            </div>

            <!-- Búsqueda de Pedido -->
            <div class="bg-white border border-zinc-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-8">
                <h2 class="text-base font-bold text-zinc-900 mb-4 flex items-center space-x-2">
                    <span>🔎</span>
                    <span>Buscar tu Compra</span>
                </h2>

                <form action="{{ route('facturacion.buscar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 mb-1">Número de Pedido *</label>
                        <input type="number" name="pedido_id" value="{{ request('pedido_id') }}" placeholder="Ej. 1024" required class="w-full bg-zinc-50 text-sm px-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 mb-1">Correo de la Compra *</label>
                        <input type="email" name="correo" value="{{ request('correo') }}" placeholder="correo@ejemplo.com" required class="w-full bg-zinc-50 text-sm px-4 py-3 rounded-2xl border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-[#88674B]/50">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-[#88674B] hover:bg-[#74563C] text-white font-bold text-xs uppercase tracking-wider py-3.5 px-6 rounded-2xl transition-all shadow-md active:scale-95">
                            Buscar Pedido
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resultado de la Búsqueda -->
            @if($buscado)
                @if($pedido)
                    <div class="bg-white border border-zinc-200 rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
                        <!-- Info Pedido -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-zinc-150 gap-4">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-xl font-bold text-zinc-950 font-mono">Pedido #{{ $pedido->id }}</h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider {{ $pedido->estado === 'completado' || $pedido->estado === 'entregado' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </div>
                                <p class="text-xs text-zinc-500 mt-1">Cliente: <strong class="text-zinc-800 font-semibold">{{ $pedido->nombre_cliente }}</strong> ({{ $pedido->correo_cliente }})</p>
                                <p class="text-xs text-zinc-500">Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }} hs</p>
                            </div>
                            <div class="sm:text-right">
                                <span class="text-xs text-zinc-500 block uppercase font-bold tracking-wider">Total de la Compra</span>
                                <span class="text-2xl font-extrabold text-amber-950 font-sans">$ {{ number_format($pedido->total, 2, '.', ',') }} MXN</span>
                            </div>
                        </div>

                        <!-- Estado de Facturación -->
                        @if($pedido->factura_estado === 'facturado')
                            <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl space-y-3">
                                <div class="flex items-center space-x-2 text-emerald-900 font-bold text-sm sm:text-base">
                                    <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>¡Factura Emitida Exitosamente!</span>
                                </div>
                                <div class="text-xs text-emerald-900 space-y-1 font-mono">
                                    <p><strong>Folio Fiscal (UUID):</strong> {{ $pedido->factura_uuid }}</p>
                                    <p><strong>RFC Receptor:</strong> {{ $pedido->rfc_receptor }}</p>
                                    <p><strong>Razón Social:</strong> {{ $pedido->razon_social }}</p>
                                </div>

                                <div class="pt-2 flex flex-wrap gap-3">
                                    <a href="{{ route('facturacion.descargar.pdf', $pedido->id) }}" target="_blank" class="inline-flex items-center space-x-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Descargar Factura PDF</span>
                                    </a>

                                    <a href="{{ route('facturacion.descargar.xml', $pedido->id) }}" target="_blank" class="inline-flex items-center space-x-2 bg-zinc-800 hover:bg-zinc-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Descargar Archivo XML</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Formulario para Emitir Factura -->
                            <div>
                                <h3 class="text-sm font-bold text-zinc-900 mb-3 flex items-center space-x-2">
                                    <span>📝</span>
                                    <span>Ingresa tus Datos Fiscales (SAT CFDI 4.0)</span>
                                </h3>

                                @if($pedido->factura_estado === 'error')
                                    <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800">
                                        <strong>Último intento:</strong> {{ $pedido->factura_error }}
                                    </div>
                                @endif

                                <form action="{{ route('facturacion.solicitar') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-700 mb-1">RFC del Receptor *</label>
                                            <input type="text" name="rfc_receptor" value="{{ old('rfc_receptor', $pedido->rfc_receptor) }}" placeholder="Ej. XAXX010101000" maxlength="13" required class="w-full bg-zinc-50 text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 uppercase font-mono focus:ring-2 focus:ring-[#88674B]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-700 mb-1">Razón Social / Nombre Fiscal *</label>
                                            <input type="text" name="razon_social" value="{{ old('razon_social', $pedido->razon_social) }}" placeholder="Nombre completo o Empresa" required class="w-full bg-zinc-50 text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 uppercase focus:ring-2 focus:ring-[#88674B]">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-700 mb-1">Régimen Fiscal (SAT) *</label>
                                            <select name="regimen_fiscal" required class="w-full bg-zinc-50 text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 focus:ring-2 focus:ring-[#88674B]">
                                                <option value="601" {{ old('regimen_fiscal', $pedido->regimen_fiscal) === '601' ? 'selected' : '' }}>601 - General de Ley Personas Morales</option>
                                                <option value="605" {{ old('regimen_fiscal', $pedido->regimen_fiscal) === '605' ? 'selected' : '' }}>605 - Sueldos y Salarios e Ingresos Asimilados</option>
                                                <option value="606" {{ old('regimen_fiscal', $pedido->regimen_fiscal) === '606' ? 'selected' : '' }}>606 - Arrendamiento</option>
                                                <option value="612" {{ old('regimen_fiscal', $pedido->regimen_fiscal) === '612' ? 'selected' : '' }}>612 - Personas Físicas con Actividades Empresariales</option>
                                                <option value="616" {{ old('regimen_fiscal', $pedido->regimen_fiscal ?? '616') === '616' ? 'selected' : '' }}>616 - Sin obligaciones fiscales</option>
                                                <option value="625" {{ old('regimen_fiscal', $pedido->regimen_fiscal) === '625' ? 'selected' : '' }}>625 - Plataformas Tecnológicas</option>
                                                <option value="626" {{ old('regimen_fiscal', $pedido->regimen_fiscal) === '626' ? 'selected' : '' }}>626 - Régimen Simplificado de Confianza (RESICO)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-700 mb-1">Uso de CFDI *</label>
                                            <select name="uso_cfdi" required class="w-full bg-zinc-50 text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 focus:ring-2 focus:ring-[#88674B]">
                                                <option value="G01" {{ old('uso_cfdi', $pedido->uso_cfdi) === 'G01' ? 'selected' : '' }}>G01 - Adquisición de mercancías</option>
                                                <option value="G03" {{ old('uso_cfdi', $pedido->uso_cfdi ?? 'G03') === 'G03' ? 'selected' : '' }}>G03 - Gastos en general</option>
                                                <option value="S01" {{ old('uso_cfdi', $pedido->uso_cfdi) === 'S01' ? 'selected' : '' }}>S01 - Sin efectos fiscales</option>
                                                <option value="CP01" {{ old('uso_cfdi', $pedido->uso_cfdi) === 'CP01' ? 'selected' : '' }}>CP01 - Pagos</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-700 mb-1">Código Postal Fiscal *</label>
                                            <input type="text" name="codigo_postal_fiscal" value="{{ old('codigo_postal_fiscal', $pedido->codigo_postal_fiscal ?? $pedido->codigo_postal) }}" maxlength="5" required class="w-full bg-zinc-50 text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 font-mono focus:ring-2 focus:ring-[#88674B]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-700 mb-1">Correo de Envío *</label>
                                            <input type="email" name="correo_facturacion" value="{{ old('correo_facturacion', $pedido->correo_facturacion ?? $pedido->correo_cliente) }}" required class="w-full bg-zinc-50 text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 focus:ring-2 focus:ring-[#88674B]">
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        <button type="submit" class="w-full bg-[#4c6f4f] hover:bg-[#3c583e] text-white font-bold text-xs uppercase tracking-wider py-4 rounded-2xl transition-all shadow-md hover:shadow-lg border border-white/20">
                                            ⚡ Timbrar y Generar Factura SAT (FastAPI)
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </div>
                @else
                    <div class="p-8 bg-rose-50 border border-rose-200 rounded-3xl text-center text-rose-800 space-y-2">
                        <span class="text-3xl">⚠️</span>
                        <h3 class="font-bold text-base">No encontramos ningún pedido</h3>
                        <p class="text-xs text-rose-700">Verifica que el número de pedido y el correo electrónico coincidan exactamente con la confirmación de tu compra.</p>
                    </div>
                @endif
            @endif

        </div>
    </div>
@endsection
