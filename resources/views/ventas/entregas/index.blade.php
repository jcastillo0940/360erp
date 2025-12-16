@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-lg shadow border border-slate-200">
        <div class="p-6 border-b flex justify-between items-center bg-slate-50">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Órdenes de Entrega</h2>
                <p class="text-sm text-slate-500">Gestión de despachos y pre-facturas</p>
            </div>
            <a href="{{ route('entregas.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold">
                <i class="fas fa-plus mr-2"></i> Nueva Orden
            </a>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
                <tr>
                    <th class="p-4">N° Orden</th>
                    <th class="p-4">Cliente</th>
                    <th class="p-4">Entrega Est.</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-right">Total Ref.</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ordenes as $o)
                    <tr class="hover:bg-blue-50">
                        <td class="p-4 font-bold text-blue-600">{{ $o->numero_orden }}</td>
                        <td class="p-4">
                            <div class="font-bold">{{ $o->cliente->razon_social }}</div>
                            @if($o->sucursal)
                                <div class="text-xs text-slate-500"><i class="fas fa-map-marker-alt"></i> {{ $o->sucursal->nombre }}
                                </div>
                            @endif
                        </td>
                        <td class="p-4">{{ $o->fecha_entrega }}</td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded text-xs font-bold {{ $o->estado == 'facturado' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ strtoupper($o->estado) }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-bold text-slate-700">B/. {{ number_format($o->total, 2) }}</td>
                        <td class="p-4 text-center space-x-2">
                            <a href="{{ route('entregas.show', $o->id) }}" class="text-blue-500 hover:text-blue-700"
                                title="Ver Detalle"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('entregas.pdf', $o->id) }}" target="_blank"
                                class="text-red-500 hover:text-red-700" title="PDF"><i class="fas fa-file-pdf"></i></a>

                            @if($o->estado == 'pendiente')
                                <a href="{{ route('entregas.edit', $o->id) }}" class="text-amber-500 hover:text-amber-700"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('entregas.convertir', $o->id) }}"
                                    onclick="return confirm('¿Crear factura fiscal de esta orden?')"
                                    class="text-green-600 hover:text-green-800" title="Facturar">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </a>
                                <form action="{{ route('entregas.destroy', $o->id) }}" method="POST" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600" title="Eliminar"><i
                                            class="fas fa-trash-alt"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No hay órdenes de entrega registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $ordenes->links() }}</div>
    </div>
@endsection