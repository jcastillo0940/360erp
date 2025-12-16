@extends('layouts.app')
@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Panel de Reparto</h1>
                <p class="text-slate-500">Gestiona las entregas programadas</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-slate-200">
            <form method="GET" action="{{ route('repartidor.dashboard') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Fecha de Entrega</label>
                    <input type="date" name="fecha" value="{{ $fecha }}"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Repartidor</label>
                    <select name="repartidor_id"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos los repartidores</option>
                        @foreach($repartidores as $r)
                            <option value="{{ $r->id }}" {{ $repartidor_id == $r->id ? 'selected' : '' }}>
                                {{ $r->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit"
                        class="w-full px-6 py-2.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-lg transition">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                </div>

                <div>
                    <a href="{{ route('repartidor.itinerario.pdf') }}?fecha={{ $fecha }}&repartidor_id={{ $repartidor_id }}"
                        target="_blank"
                        class="block w-full px-6 py-2.5 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 shadow-lg transition text-center">
                        <i class="fas fa-file-pdf mr-2"></i> Generar PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-slate-200">
            <h3 class="font-bold text-lg mb-4 text-slate-700">
                <i class="fas fa-list-check mr-2"></i>
                Entregas para {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                ({{ $ordenes_asignadas->count() }} órdenes)
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
                        <tr>
                            <th class="p-3">Orden</th>
                            <th class="p-3">Cliente</th>
                            <th class="p-3">Dirección</th>
                            <th class="p-3">Repartidor</th>
                            <th class="p-3 text-right">Total</th>
                            <th class="p-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($ordenes_asignadas as $o)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono text-blue-600 font-bold">{{ $o->numero_orden }}</td>
                                <td class="p-3">
                                    <div class="font-semibold">{{ $o->cliente->razon_social }}</div>
                                    @if($o->oc_externa)
                                        <div class="text-xs text-purple-600 font-bold">OC: {{ $o->oc_externa }}</div>
                                    @endif
                                </td>
                                <td class="p-3 text-sm">
                                    {{ $o->sucursal->direccion ?? $o->cliente->direccion }}
                                    <div class="text-xs text-slate-500">({{ $o->sucursal->nombre ?? 'Principal' }})</div>
                                </td>
                                <td class="p-3">
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-bold">
                                        {{ $o->ruta->repartidor->nombre ?? 'Sin asignar' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-bold">B/. {{ number_format($o->total, 2) }}</td>
                                <td class="p-3 text-center">
                                    <a href="{{ route('entregas.show', $o->id) }}"
                                        class="text-blue-500 hover:text-blue-700 mr-2" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('entregas.pdf', $o->id) }}" target="_blank"
                                        class="text-red-500 hover:text-red-700 mr-2" title="Imprimir">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('repartidor.check', $o->id) }}" method="POST" class="inline"
                                        id="form-entregar-{{ $o->id }}">
                                        @csrf @method('PUT')
                                        <button type="button" onclick="confirmarEntrega({{ $o->id }})"
                                            class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 font-bold"
                                            title="Marcar como entregado">
                                            <i class="fas fa-check mr-1"></i> Entregar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 italic">
                                    No hay entregas programadas para esta fecha
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmarEntrega(ordenId) {
            Swal.fire({
                title: '¿Marcar como entregado?',
                text: 'Esta acción confirmará que la orden fue entregada',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-entregar-' + ordenId).submit();
                }
            });
        }
    </script>
@endsection