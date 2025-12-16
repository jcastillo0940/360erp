@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Catálogo de Productos y Servicios</h2>
            <p class="text-sm text-slate-500">Gestione costos, precios y stock</p>
        </div>
        <a href="{{ route('items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold">
            <i class="fas fa-plus mr-2"></i> Nuevo Ítem
        </a>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
            <tr>
                <th class="p-4">Nombre</th>
                <th class="p-4">Código</th>
                <th class="p-4 text-right">Costo</th>
                <th class="p-4 text-right">Precio Base</th>
                <th class="p-4 text-right">Stock</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($items as $i)
            <tr class="hover:bg-blue-50">
                <td class="p-4 font-bold">{{ $i->nombre }}</td>
                <td class="p-4 font-mono text-slate-500">{{ $i->codigo }}</td>
                <td class="p-4 text-right">B/. {{ number_format($i->costo_unitario, 2) }}</td>
                <td class="p-4 text-right font-bold">B/. {{ number_format($i->precio_venta, 2) }}</td>
                <td class="p-4 text-right font-bold {{ $i->stock < 5 ? 'text-red-600' : 'text-green-600' }}">{{ $i->stock }}</td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('items.edit', $i->id) }}" class="text-blue-500 hover:text-blue-700" title="Editar"><i class="fas fa-edit"></i></a>
                    <a href="{{ route('items.precios', $i->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Precios por Lista"><i class="fas fa-tags"></i></a>
                    <form action="{{ route('items.destroy', $i->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-slate-400">No hay ítems registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $items->links() }}</div>
</div>
@endsection