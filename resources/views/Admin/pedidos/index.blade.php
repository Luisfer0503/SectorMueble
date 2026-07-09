@extends('layouts.admin')

@section('contenido')
    <!-- Header -->
    <div class="pb-6 border-b border-zinc-200 mb-8">
        <h1 class="serif-title text-3xl font-bold text-zinc-950">Gestión de Pedidos</h1>
        <p class="text-zinc-500 text-sm mt-1">Supervisa y actualiza el estado de los pedidos realizados por los clientes.</p>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-zinc-200 rounded shadow-sm overflow-hidden">
        @if($pedidos->isEmpty())
            <div class="text-center py-20 text-zinc-500">
                No se han registrado pedidos en el sistema todavía.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-zinc-50 text-xs font-bold text-zinc-450 border-b border-zinc-200 uppercase tracking-wider">
                            <th class="p-4 pl-6">Nº Pedido</th>
                            <th class="p-4">Cliente</th>
                            <th class="p-4">Fecha</th>
                            <th class="p-4">Descuento</th>
                            <th class="p-4">Total Pagado</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-right pr-6">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($pedidos as $pedido)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <!-- ID Pedido -->
                                <td class="p-4 pl-6 font-mono font-bold text-zinc-900 text-sm">
                                    #{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                
                                <!-- Cliente -->
                                <td class="p-4">
                                    <span class="font-semibold text-zinc-900 block">{{ $pedido->nombre_cliente }}</span>
                                    <span class="text-xs text-zinc-450 block mt-0.5">{{ $pedido->correo_cliente }}</span>
                                </td>
                                
                                <!-- Fecha -->
                                <td class="p-4 text-zinc-500 text-xs">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </td>
                                
                                <!-- Descuento -->
                                <td class="p-4 text-zinc-650">
                                    @if($pedido->cupon_codigo)
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 rounded px-2 py-0.5 w-fit uppercase font-mono tracking-wider">{{ $pedido->cupon_codigo }}</span>
                                            <span class="text-[10px] text-zinc-500 mt-1 font-sans">-$ {{ number_format($pedido->descuento, 2, '.', ',') }}</span>
                                        </div>
                                    @else
                                        <span class="text-zinc-400 text-xs">Ninguno</span>
                                    @endif
                                </td>
                                
                                <!-- Total -->
                                <td class="p-4 font-bold text-zinc-900 font-sans">
                                    $ {{ number_format($pedido->total, 2, '.', ',') }}
                                </td>
                                
                                <!-- Estado -->
                                <td class="p-4">
                                    @if($pedido->estado === 'pendiente')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                            Pendiente
                                        </span>
                                    @elseif($pedido->estado === 'procesado')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                            Procesado
                                        </span>
                                    @elseif($pedido->estado === 'enviado')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-800 border border-indigo-200">
                                            Enviado
                                        </span>
                                    @elseif($pedido->estado === 'entregado')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Entregado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                            Cancelado
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Acciones -->
                                <td class="p-4 text-right pr-6">
                                    <a href="{{ route('admin.pedidos.detalle', $pedido->id) }}" class="text-xs font-bold text-amber-850 hover:underline">Ver Detalles</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-zinc-200">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
@endsection
