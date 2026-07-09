@extends('layouts.admin')

@section('contenido')
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="serif-title text-3xl font-bold text-zinc-950">Hola, {{ auth()->user()->name }}</h1>
        <p class="text-zinc-505 text-sm mt-1">Este es el resumen operativo de tu tienda Sector Mueble para el día de hoy.</p>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ventas Totales -->
        <div class="bg-white p-6 rounded border border-zinc-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Ventas Netas</span>
                <span class="text-2xl font-bold text-zinc-900 block mt-2 font-sans">$ {{ number_format($ventasTotales, 2, '.', ',') }}</span>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                <span>Pedidos aprobados</span>
                <span class="text-emerald-700 font-semibold flex items-center">
                    <svg class="h-3 w-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Activo
                </span>
            </div>
        </div>

        <!-- Pedidos Totales -->
        <div class="bg-white p-6 rounded border border-zinc-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Pedidos Recibidos</span>
                <span class="text-2xl font-bold text-zinc-900 block mt-2 font-sans">{{ $pedidosTotales }}</span>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                <span>Pendientes por enviar</span>
                <span class="text-amber-700 font-semibold font-sans">{{ $pedidosPendientes }}</span>
            </div>
        </div>

        <!-- Muebles en Catálogo -->
        <div class="bg-white p-6 rounded border border-zinc-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Muebles Catalogados</span>
                <span class="text-2xl font-bold text-zinc-900 block mt-2 font-sans">{{ $mueblesTotales }}</span>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                <a href="{{ route('admin.productos') }}" class="text-amber-850 hover:underline">Ver catálogo</a>
            </div>
        </div>

        <!-- Clientes Registrados -->
        <div class="bg-white p-6 rounded border border-zinc-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Clientes Registrados</span>
                <span class="text-2xl font-bold text-zinc-900 block mt-2 font-sans">{{ $clientesTotales }}</span>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                <span>Cuentas de compradores</span>
            </div>
        </div>
    </div>

    <!-- Alert / Inventory Check & Recent Orders Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Orders (Left - Col 2) -->
        <div class="lg:col-span-2 bg-white border border-zinc-200 rounded p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-150 mb-6">
                <h2 class="serif-title text-lg font-bold text-zinc-950">Pedidos Recientes</h2>
                <a href="{{ route('admin.pedidos') }}" class="text-xs font-bold text-amber-850 hover:underline uppercase tracking-wider">Ver todos</a>
            </div>

            @if($pedidosRecientes->isEmpty())
                <div class="text-center py-12 text-zinc-500 text-sm">
                    No se han registrado pedidos en la tienda aún.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-xs font-bold text-zinc-450 border-b border-zinc-100 uppercase tracking-wider">
                                <th class="pb-3">Código</th>
                                <th class="pb-3">Cliente</th>
                                <th class="pb-3">Fecha</th>
                                <th class="pb-3">Total</th>
                                <th class="pb-3">Estado</th>
                                <th class="pb-3 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50">
                            @foreach($pedidosRecientes as $pedido)
                                <tr>
                                    <td class="py-3 font-mono font-bold text-zinc-900">#{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3 font-medium text-zinc-900">{{ $pedido->nombre_cliente }}</td>
                                    <td class="py-3 text-zinc-500 text-xs">{{ $pedido->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 font-semibold text-zinc-900 font-sans">$ {{ number_format($pedido->total, 2, '.', ',') }}</td>
                                    <td class="py-3">
                                        @if($pedido->estado === 'pendiente')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">Pendiente</span>
                                        @elseif($pedido->estado === 'procesado')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">Procesado</span>
                                        @elseif($pedido->estado === 'enviado')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-800 border border-indigo-200">Enviado</span>
                                        @elseif($pedido->estado === 'entregado')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">Entregado</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">Cancelado</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('admin.pedidos.detalle', $pedido->id) }}" class="text-xs font-semibold text-amber-850 hover:underline">Gestionar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Inventory Alerts & Actions (Right - Col 1) -->
        <div class="lg:col-span-1 bg-white border border-zinc-200 rounded p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-150 mb-6">
                <h2 class="serif-title text-lg font-bold text-zinc-950">Control de Inventario</h2>
            </div>

            @if($mueblesStockBajo->isEmpty())
                <div class="bg-emerald-50 border border-emerald-200 rounded p-4 text-xs text-emerald-800 flex items-start space-x-2">
                    <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <span class="font-bold block">Stock saludable</span>
                        <p class="mt-0.5">Todos los muebles del catálogo cuentan con inventario suficiente por encima de 5 unidades.</p>
                    </div>
                </div>
            @else
                <div class="bg-rose-50 border border-rose-200 rounded p-4 text-xs text-rose-800 flex items-start space-x-2 mb-4">
                    <svg class="h-5 w-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <span class="font-bold block">¡Alerta de Stock Bajo!</span>
                        <p class="mt-0.5">Hay {{ $mueblesStockBajo->count() }} muebles con stock crítico de 5 o menos unidades.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($mueblesStockBajo as $bajo)
                        <div class="flex items-center justify-between text-sm p-3 bg-zinc-50 border border-zinc-150 rounded hover:bg-zinc-100 transition-colors">
                            <div class="truncate pr-2">
                                <span class="font-semibold text-zinc-900 block truncate">{{ $bajo->nombre }}</span>
                                <span class="text-xs text-zinc-500">Categoría: {{ $bajo->categoria }}</span>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="bg-rose-100 text-rose-900 text-xs font-bold px-2.5 py-1 rounded block">Stock: {{ $bajo->stock }}</span>
                                <a href="{{ route('admin.productos.editar', $bajo->id) }}" class="text-[10px] text-amber-850 hover:underline block mt-1 font-semibold uppercase tracking-wider">Ajustar</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@endsection
