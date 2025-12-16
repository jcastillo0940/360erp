@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Gestión de Listas de Precios</h2>
            <p class="text-sm text-slate-500">Defina precios especiales por grupos de clientes</p>
        </div>
        <a href="{{ route('listas_precios.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold">
            <i class="fas fa-plus mr-2"></i> Nueva Lista
        </a>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
            <tr>
                <th class="p-4">Nombre de Lista</th>
                <th class="p-4">Estado</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($listas as $lista)
            <tr class="hover:bg-blue-50">
                <td class="p-4 font-bold text-blue-600">{{ $lista->nombre }}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $lista->activa ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $lista->activa ? 'ACTIVA' : 'INACTIVA' }}
                    </span>
                </td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('listas_precios.edit', $lista->id) }}" class="text-blue-500 hover:text-blue-700" title="Editar"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('listas_precios.destroy', $lista->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="p-8 text-center text-slate-400">No hay listas de precios creadas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $listas->links() }}</div>
</div>
@endsection