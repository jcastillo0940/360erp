@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">Gestión de Repartidores</h2>
        <a href="{{ route('repartidores.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold"><i class="fas fa-plus"></i> Nuevo</a>
    </div>
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
            <tr><th>Nombre</th><th>Teléfono</th><th>Pago</th><th>Tarifa</th><th>Estado</th><th class="text-center">Acciones</th></tr>
        </thead>
        <tbody>
            @forelse($repartidores as $r)
            <tr class="hover:bg-blue-50">
                <td class="p-4 font-bold">{{ $r->nombre }}</td>
                <td class="p-4">{{ $r->telefono }}</td>
                <td class="p-4">{{ $r->tipo_pago }}</td>
                <td class="p-4">B/. {{ number_format($r->tarifa, 2) }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold {{ $r->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $r->activo ? 'Activo' : 'Inactivo' }}</span></td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('repartidores.edit', $r->id) }}" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('repartidores.destroy', $r->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')<button class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-slate-400">No hay repartidores.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection