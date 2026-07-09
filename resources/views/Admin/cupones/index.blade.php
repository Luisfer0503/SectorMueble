@extends('layouts.admin')

@section('contenido')
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-zinc-200 mb-8 gap-4">
        <div>
            <h1 class="serif-title text-3xl font-bold text-zinc-950">Cupones de Descuento</h1>
            <p class="text-zinc-500 text-sm mt-1">Crea y administra los códigos promocionales y campañas de descuento.</p>
        </div>
        <div>
            <a href="{{ route('admin.cupones.crear') }}" class="bg-amber-800 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded shadow transition-colors">
                + Crear Cupón
            </a>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white border border-zinc-200 rounded shadow-sm overflow-hidden max-w-4xl">
        @if($cupones->isEmpty())
            <div class="text-center py-20 text-zinc-500">
                No hay cupones creados actualmente.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-zinc-50 text-xs font-bold text-zinc-450 border-b border-zinc-200 uppercase tracking-wider">
                            <th class="p-4 pl-6">Código del Cupón</th>
                            <th class="p-4">Tipo</th>
                            <th class="p-4">Valor del Descuento</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-right pr-6">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($cupones as $cupon)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <!-- Código -->
                                <td class="p-4 pl-6 font-mono font-bold text-sm text-zinc-900 tracking-wider">
                                    {{ $cupon->codigo }}
                                </td>
                                
                                <!-- Tipo -->
                                <td class="p-4 text-zinc-500">
                                    @if($cupon->tipo === 'porcentaje')
                                        Porcentaje (%)
                                    @else
                                        Monto Fijo ($)
                                    @endif
                                </td>
                                
                                <!-- Valor -->
                                <td class="p-4 font-semibold text-zinc-900 font-sans">
                                    @if($cupon->tipo === 'porcentaje')
                                        {{ number_format($cupon->valor, 0) }} %
                                    @else
                                        $ {{ number_format($cupon->valor, 2, '.', ',') }} MXN
                                    @endif
                                </td>
                                
                                <!-- Estado -->
                                <td class="p-4">
                                    @if($cupon->activo)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-800 border border-zinc-200">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Acciones -->
                                <td class="p-4 text-right pr-6 space-x-3">
                                    <a href="{{ route('admin.cupones.editar', $cupon->id) }}" class="text-xs font-bold text-amber-850 hover:underline">Editar</a>
                                    <a href="{{ route('admin.cupones.eliminar', $cupon->id) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar este cupón?')" class="text-xs font-bold text-rose-600 hover:underline">Eliminar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-zinc-200">
                {{ $cupones->links() }}
            </div>
        @endif
    </div>
@endsection
