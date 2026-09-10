@extends('layouts.admin')

@section('contenido')
    <!-- Header with breadcrumbs -->
    <div class="pb-6 border-b border-zinc-200 mb-8">
        <nav class="flex text-xs font-medium text-zinc-500 space-x-2 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-850">Inicio</a>
            <span>/</span>
            <a href="{{ route('admin.pedidos') }}" class="hover:text-amber-850">Pedidos</a>
            <span>/</span>
            <span class="text-zinc-800">Detalle</span>
        </nav>
        <h1 class="serif-title text-3xl font-bold text-zinc-950">Pedido Nº #{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</h1>
        <p class="text-zinc-500 text-sm mt-1">Realizado el {{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i \h\s') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Detalles y Artículos (Izquierda - Col 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Artículos Comprados -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                <h2 class="serif-title text-lg font-bold text-zinc-950 pb-3 border-b border-zinc-100 mb-4">Artículos en este Pedido</h2>
                
                <div class="divide-y divide-zinc-100 text-sm">
                    @php
                        $subtotalProductos = 0;
                    @endphp
                    @foreach($pedido->detalles as $det)
                        @php
                            $itemTotal = $det->precio * $det->cantidad;
                            $subtotalProductos += $itemTotal;
                        @endphp
                        <div class="flex items-center justify-between py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <h4 class="font-semibold text-zinc-900">{{ $det->nombre_producto }}</h4>
                                    <span class="text-xs text-zinc-500">{{ $det->cantidad }} unidades x $ {{ number_format($det->precio, 2, '.', ',') }} MXN</span>
                                </div>
                            </div>
                            <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($itemTotal, 2, '.', ',') }} MXN</span>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen de Precios -->
                @php
                    $costoEnvio = $pedido->total - $subtotalProductos + $pedido->descuento;
                @endphp
                <div class="bg-zinc-50 rounded border border-zinc-150 p-4 space-y-2 text-xs mt-6">
                    <div class="flex justify-between text-zinc-500">
                        <span>Subtotal de productos</span>
                        <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($subtotalProductos, 2, '.', ',') }}</span>
                    </div>
                    @if($pedido->cupon_codigo)
                        <div class="flex justify-between text-zinc-500">
                            <span>Descuento aplicado (Cupón: {{ $pedido->cupon_codigo }})</span>
                            <span class="font-semibold text-rose-700 font-sans">-$ {{ number_format($pedido->descuento, 2, '.', ',') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-zinc-500">
                        <span>Costo de envío</span>
                        @if($costoEnvio <= 0)
                            <span class="font-bold text-emerald-700 uppercase">Gratis</span>
                        @else
                            <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($costoEnvio, 2, '.', ',') }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-sm text-zinc-950 pt-2 border-t border-zinc-200">
                        <span class="font-bold">Total Pagado</span>
                        <span class="text-base font-bold font-sans text-amber-800">$ {{ number_format($pedido->total, 2, '.', ',') }} MXN</span>
                    </div>
                </div>
            </div>

            <!-- Datos de Entrega -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                <h2 class="serif-title text-lg font-bold text-zinc-950 pb-3 border-b border-zinc-100 mb-4">Información de Envío</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <span class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-1">Destinatario</span>
                        <p class="font-semibold text-zinc-900">{{ $pedido->nombre_cliente }}</p>
                        <p class="text-zinc-500 mt-1 text-xs">Correo: {{ $pedido->correo_cliente }}</p>
                        <p class="text-zinc-500 text-xs">Teléfono: {{ $pedido->telefono_cliente }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-1">Dirección</span>
                        <p class="font-semibold text-zinc-900">{{ $pedido->direccion_envio }}</p>
                        <p class="text-zinc-500 mt-1 text-xs">Código Postal: {{ $pedido->codigo_postal }}</p>
                        <p class="text-zinc-500 text-xs">Ciudad: {{ $pedido->ciudad }}</p>
                    </div>
                </div>
            </div>

            <!-- Gestión de Facturación Electrónica SAT (FastAPI) -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                    <h2 class="serif-title text-lg font-bold text-zinc-950">Facturación Electrónica SAT (FastAPI)</h2>
                    @if($pedido->factura_estado === 'facturado')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Facturado</span>
                    @elseif($pedido->factura_estado === 'error')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">Error en Timbrado</span>
                    @elseif($pedido->factura_estado === 'pendiente')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">Pendiente</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-zinc-100 text-zinc-600 border border-zinc-200">No Solicitada</span>
                    @endif
                </div>

                @if($pedido->factura_estado === 'facturado')
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2 text-xs text-emerald-900 font-mono">
                        <p><strong>Folio Fiscal (UUID):</strong> {{ $pedido->factura_uuid }}</p>
                        <p><strong>RFC Receptor:</strong> {{ $pedido->rfc_receptor }}</p>
                        <p><strong>Razón Social:</strong> {{ $pedido->razon_social }}</p>
                        <div class="pt-2 flex gap-3">
                            <a href="{{ route('facturacion.descargar.pdf', $pedido->id) }}" target="_blank" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-sans font-bold rounded shadow-xs">Ver PDF</a>
                            <a href="{{ route('facturacion.descargar.xml', $pedido->id) }}" target="_blank" class="px-3 py-1.5 bg-zinc-800 hover:bg-zinc-900 text-white font-sans font-bold rounded shadow-xs">Ver XML</a>
                        </div>
                    </div>
                @else
                    <form action="{{ route('admin.pedidos.facturar', $pedido->id) }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        @if($pedido->factura_error)
                            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded font-mono">
                                <strong>Error FastAPI:</strong> {{ $pedido->factura_error }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">RFC Receptor</label>
                                <input type="text" name="rfc_receptor" value="{{ old('rfc_receptor', $pedido->rfc_receptor) }}" placeholder="XAXX010101000" maxlength="13" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2 uppercase font-mono">
                            </div>
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Razón Social</label>
                                <input type="text" name="razon_social" value="{{ old('razon_social', $pedido->razon_social) }}" placeholder="Nombre o Razón Social" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2 uppercase">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Régimen Fiscal</label>
                                <select name="regimen_fiscal" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2">
                                    <option value="601" {{ $pedido->regimen_fiscal === '601' ? 'selected' : '' }}>601 - General de Ley Personas Morales</option>
                                    <option value="605" {{ $pedido->regimen_fiscal === '605' ? 'selected' : '' }}>605 - Sueldos y Salarios</option>
                                    <option value="606" {{ $pedido->regimen_fiscal === '606' ? 'selected' : '' }}>606 - Arrendamiento</option>
                                    <option value="612" {{ $pedido->regimen_fiscal === '612' ? 'selected' : '' }}>612 - Personas Físicas Act. Empresariales</option>
                                    <option value="616" {{ ($pedido->regimen_fiscal ?? '616') === '616' ? 'selected' : '' }}>616 - Sin obligaciones fiscales</option>
                                    <option value="626" {{ $pedido->regimen_fiscal === '626' ? 'selected' : '' }}>626 - RESICO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Uso de CFDI</label>
                                <select name="uso_cfdi" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2">
                                    <option value="G01" {{ $pedido->uso_cfdi === 'G01' ? 'selected' : '' }}>G01 - Adquisición de mercancías</option>
                                    <option value="G03" {{ ($pedido->uso_cfdi ?? 'G03') === 'G03' ? 'selected' : '' }}>G03 - Gastos en general</option>
                                    <option value="S01" {{ $pedido->uso_cfdi === 'S01' ? 'selected' : '' }}>S01 - Sin efectos fiscales</option>
                                    <option value="CP01" {{ $pedido->uso_cfdi === 'CP01' ? 'selected' : '' }}>CP01 - Pagos</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">CP Fiscal</label>
                                <input type="text" name="codigo_postal_fiscal" value="{{ old('codigo_postal_fiscal', $pedido->codigo_postal_fiscal ?? $pedido->codigo_postal) }}" maxlength="5" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2 font-mono">
                            </div>
                            <div>
                                <label class="block font-semibold text-zinc-700 mb-1">Correo Envío</label>
                                <input type="email" name="correo_facturacion" value="{{ old('correo_facturacion', $pedido->correo_facturacion ?? $pedido->correo_cliente) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded px-3 py-2">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#4c6f4f] hover:bg-[#3c583e] text-white font-bold text-xs uppercase tracking-wider py-3 rounded transition-colors shadow">
                            ⚡ Emitir Factura SAT vía FastAPI
                        </button>
                    </form>
                @endif
            </div>

        </div>

        <!-- Estado y Gestión (Derecha - Col 1) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Cambio de Estado -->
            <div class="bg-white border border-zinc-200 rounded p-6 shadow-sm sticky top-24">
                <h2 class="serif-title text-lg font-bold text-zinc-950 pb-3 border-b border-zinc-100 mb-4">Estado del Pedido</h2>
                
                <div class="mb-6 flex items-center justify-between">
                    <span class="text-xs text-zinc-500">Estado actual:</span>
                    @if($pedido->estado === 'pendiente')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">Pendiente</span>
                    @elseif($pedido->estado === 'procesado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">Procesado</span>
                    @elseif($pedido->estado === 'enviado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-800 border border-indigo-200">Enviado</span>
                    @elseif($pedido->estado === 'entregado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">Entregado</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">Cancelado</span>
                    @endif
                </div>

                <form action="{{ route('admin.pedidos.actualizar_estado', $pedido->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="estado" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Actualizar Estado</label>
                        <select name="estado" id="estado" class="w-full bg-zinc-50 border border-zinc-200 rounded text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>Pendiente (Recibido)</option>
                            <option value="procesado" {{ $pedido->estado === 'procesado' ? 'selected' : '' }}>Procesado (Pagado/Empacado)</option>
                            <option value="enviado" {{ $pedido->estado === 'enviado' ? 'selected' : '' }}>Enviado (En camino)</option>
                            <option value="entregado" {{ $pedido->estado === 'entregado' ? 'selected' : '' }}>Entregado (Finalizado)</option>
                            <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded transition-colors shadow">
                        Actualizar Estado
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-zinc-150 text-center">
                    <a href="{{ route('admin.pedidos') }}" class="text-xs font-bold text-zinc-500 hover:text-amber-850 hover:underline">
                        &larr; Volver al listado
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
