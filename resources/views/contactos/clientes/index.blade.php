@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Directorio de Clientes</h2>
            <p class="text-sm text-slate-500">Gestione la información de sus compradores</p>
        </div>
        <a href="{{ route('clientes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold">
            <i class="fas fa-plus mr-2"></i> Nuevo Cliente
        </a>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
            <tr>
                <th class="p-4">Cliente</th>
                <th class="p-4">RUC / Cédula</th>
                <th class="p-4">Condición</th>
                <th class="p-4">Lista de Precio</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($clientes as $c)
            <tr class="hover:bg-blue-50">
                <td class="p-4 font-bold">{{ $c->razon_social }}</td>
                <td class="p-4">{{ $c->identificacion }}</td>
                <td class="p-4">{{ str_replace('credito_', 'Crédito ', $c->condicion_pago) }}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800">
                        {{ $c->listaPrecio->nombre ?? 'Precio Base' }}
                    </span>
                </td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('clientes.edit', $c->id) }}" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('clientes.destroy', $c->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-8 text-center text-slate-400">No hay clientes.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $clientes->links() }}</div>
</div>
@endsection