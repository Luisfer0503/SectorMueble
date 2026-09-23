@extends('layouts.app')

@section('titulo', 'Mi Perfil y Mis Compras | Sector Mueble')

@section('contenido')
<div class="bg-[#FAF8F5] min-h-screen py-10 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header del Perfil -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-zinc-200/80 mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#88674B] text-white flex items-center justify-center font-extrabold text-2xl sm:text-3xl shadow-md border-2 border-white shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="serif-title text-xl sm:text-2xl font-bold text-zinc-900">{{ $user->name }}</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 font-medium">{{ $user->email }}</p>
                    <div class="mt-1.5 flex flex-wrap items-center gap-2">
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
                <a href="{{ route('logout') }}" class="w-full sm:w-auto text-center px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-colors border border-rose-200/80">
                    Cerrar Sesión
                </a>
            </div>
        </div>

        @if(session('exito'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('exito') }}</span>
            </div>
        @endif

        <!-- SECCIÓN 1: MIS COMPRAS / HISTORIAL DE PEDIDOS -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-zinc-200/80 mb-8">
            <div class="border-b border-zinc-100 pb-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="serif-title text-lg sm:text-xl font-bold text-zinc-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#88674B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Mis Compras y Pedidos</span>
                    </h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Historial de tus compras exitosas, estado de entrega y facturas SAT.</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-zinc-100 text-zinc-700 rounded-full w-fit">
                    {{ count($pedidos ?? []) }} {{ count($pedidos ?? []) === 1 ? 'Pedido' : 'Pedidos' }}
                </span>
            </div>

            @if(empty($pedidos) || count($pedidos) === 0)
                <div class="py-12 text-center bg-zinc-50/60 rounded-2xl border border-dashed border-zinc-200">
                    <div class="w-16 h-16 bg-amber-50 text-[#88674B] rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-zinc-800">Aún no has realizado compras</h3>
                    <p class="text-xs text-zinc-500 max-w-sm mx-auto mt-1 mb-6">Explora nuestro catálogo de muebles artesanales y realiza tu primera compra con envío rápido.</p>
                    <a href="{{ route('catalogo') }}" class="inline-flex items-center space-x-2 bg-[#88674B] hover:bg-[#6f533b] text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded-xl shadow-sm transition-all">
                        <span>Ver Catálogo de Muebles</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($pedidos as $pedido)
                        @php
                            $numPedido = str_pad($pedido->id, 5, '0', STR_PAD_LEFT);
                            $estadoNorm = strtolower($pedido->estado);
                        @endphp
                        <div class="bg-zinc-50/50 hover:bg-white rounded-2xl border border-zinc-200 transition-all duration-200 overflow-hidden shadow-xs">
                            
                            <!-- Header de la tarjeta del pedido -->
                            <div class="bg-zinc-100/70 px-5 py-3.5 border-b border-zinc-200/70 flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <span class="font-extrabold text-sm text-zinc-900">Pedido #{{ $numPedido }}</span>
                                    <span class="text-xs text-zinc-500 font-medium">| {{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    @if(in_array($estadoNorm, ['procesando', 'pagado']))
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>✓ Pagado / Procesando</span>
                                        </span>
                                    @elseif($estadoNorm === 'procesado')
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                                            <span>✓ Procesado</span>
                                        </span>
                                    @elseif($estadoNorm === 'enviado')
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300">
                                            <span>🚚 En Camino</span>
                                        </span>
                                    @elseif(in_array($estadoNorm, ['entregado', 'completado']))
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-800 border border-teal-300">
                                            <span>📦 Entregado</span>
                                        </span>
                                    @elseif($estadoNorm === 'contacto_agente')
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-300">
                                            <span>💬 Contacto a Agente</span>
                                        </span>
                                    @elseif($estadoNorm === 'cancelado')
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                            <span>✕ Cancelado</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            <span>⌛ Pendiente</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Contenido del pedido -->
                            <div class="p-5">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Lista de Artículos -->
                                    <div class="md:col-span-2 space-y-3">
                                        <h4 class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Productos Comprados</h4>
                                        <div class="space-y-2.5">
                                            @foreach($pedido->detalles as $detalle)
                                                <div class="flex items-center justify-between text-xs bg-white p-2.5 rounded-xl border border-zinc-100">
                                                    <div class="flex items-center space-x-3">
                                                        @if($detalle->producto && $detalle->producto->imagen)
                                                            <img src="{{ asset('storage/' . $detalle->producto->imagen) }}" alt="{{ $detalle->nombre_producto }}" class="w-10 h-10 object-cover rounded-lg border border-zinc-200 shrink-0">
                                                        @else
                                                            <div class="w-10 h-10 bg-zinc-100 rounded-lg flex items-center justify-center text-zinc-400 shrink-0">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="font-bold text-zinc-900">{{ $detalle->nombre_producto }}</p>
                                                            <p class="text-zinc-500 font-medium">{{ $detalle->cantidad }} x ${{ number_format($detalle->precio, 2) }} MXN</p>
                                                        </div>
                                                    </div>
                                                    <span class="font-extrabold text-zinc-800">${{ number_format($detalle->precio * $detalle->cantidad, 2) }} MXN</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Datos de Entrega y Total -->
                                    <div class="bg-zinc-100/50 p-4 rounded-xl border border-zinc-200/80 flex flex-col justify-between">
                                        <div>
                                            <h4 class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Dirección de Entrega</h4>
                                            <p class="text-xs font-bold text-zinc-900">{{ $pedido->nombre_cliente }}</p>
                                            <p class="text-xs text-zinc-600 mt-1">{{ $pedido->direccion_envio }}</p>
                                            <p class="text-xs text-zinc-600">{{ $pedido->ciudad }}, C.P. {{ $pedido->codigo_postal }}</p>
                                            <p class="text-xs text-zinc-500 mt-1">Tel: {{ $pedido->telefono_cliente }}</p>
                                        </div>

                                        <div class="mt-4 pt-3 border-t border-zinc-200">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-bold text-zinc-600 uppercase">Total Pagado:</span>
                                                <span class="text-base font-extrabold text-[#4c6f4f]">${{ number_format($pedido->total, 2) }} MXN</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de Acción -->
                                <div class="mt-4 pt-4 border-t border-zinc-200/80 flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('pedido.confirmado', $pedido->id) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-[#88674B] hover:bg-[#6f533b] text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Ver Recibo Completo</span>
                                        </a>

                                        <a href="{{ route('pedido.contactar_agente', $pedido->id) }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            <span>Contactar Agente</span>
                                        </a>
                                    </div>

                                    <!-- Descarga de Facturación SAT -->
                                    <div class="flex items-center gap-2">
                                        @if($pedido->factura_pdf_url || $pedido->factura_xml_url)
                                            <span class="text-xs font-bold text-emerald-700 mr-1">Factura SAT:</span>
                                            @if($pedido->factura_pdf_url)
                                                <a href="{{ route('facturacion.descargar.pdf', $pedido->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold transition-colors">
                                                    <span>PDF</span>
                                                </a>
                                            @endif
                                            @if($pedido->factura_xml_url)
                                                <a href="{{ route('facturacion.descargar.xml', $pedido->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold transition-colors">
                                                    <span>XML</span>
                                                </a>
                                            @endif
                                        @elseif($pedido->requiere_factura)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                <span>Factura Solicitada / En Proceso</span>
                                            </span>
                                        @else
                                            <a href="{{ route('facturacion.index') }}" class="text-xs font-semibold text-zinc-500 hover:text-[#88674B] underline">
                                                Solicitar Factura SAT
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- SECCIÓN 2: DATOS PERSONALES -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-zinc-200/80">
            <div class="border-b border-zinc-100 pb-4 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="serif-title text-lg sm:text-xl font-bold text-zinc-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#88674B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Información Personal</span>
                    </h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Actualiza tus datos de contacto y contraseña en Sector Mueble.</p>
                </div>
            </div>

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
