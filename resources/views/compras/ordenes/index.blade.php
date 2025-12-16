@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-lg shadow border border-slate-200">
        <div class="p-6 border-b flex justify-between items-center bg-slate-50">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Órdenes de Compra</h2>
                <p class="text-sm text-slate-500">Gestione sus pedidos</p>
            </div>
            <a href="{{ route('ordenes_compra.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center gap-2"><i
                    class="fas fa-plus"></i> Nueva Orden</a>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                <tr>
                    <th class="p-4">N° Orden</th>
                    <th class="p-4">Proveedor</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ordenes as $o)
                    <tr class="hover:bg-blue-50">
                        <td class="p-4 font-bold text-blue-600">{{ $o->numero_orden }}</td>
                        <td class="p-4">{{ $o->proveedor->razon_social }}</td>
                        <td class="p-4"><span
                                class="px-2 py-1 rounded text-xs font-bold {{ $o->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">{{ strtoupper($o->estado) }}</span>
                        </td>
                        <td class="p-4 text-right font-bold">B/. {{ number_format($o->total, 2) }}</td>
                        <td class="p-4 text-center space-x-2">
                            <a href="{{ route('ordenes_compra.show', $o->id) }}" class="text-blue-500" title="Ver"><i
                                    class="fas fa-eye"></i></a>
                            @if(in_array($o->estado, ['pendiente', 'generada']))
                                <a href="{{ route('ordenes_compra.edit', $o->id) }}" class="text-amber-500 hover:text-amber-700"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ordenes_compra.destroy', $o->id) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600" title="Eliminar"><i
                                            class="fas fa-trash-alt"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400">Sin registros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection