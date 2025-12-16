@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">Rutas de Reparto</h2>
        <a href="{{ route('rutas_reparto.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold"><i class="fas fa-plus"></i> Nueva Ruta</a>
    </div>
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
            <tr><th>Ruta</th><th>Repartidor</th><th>Vehículo</th><th>Días Activos</th><th class="text-center">Carga</th><th class="text-center">Acciones</th></tr>
        </thead>
        <tbody>
            @forelse($rutas as $r)
            <tr class="hover:bg-blue-50">
                <td class="p-4 font-bold">{{ $r->nombre }}</td>
                <td class="p-4">{{ $r->repartidor->nombre ?? 'Sin Asignar' }}</td>
                <td class="p-4">{{ $r->vehiculo }} ({{ $r->placa }})</td>
                <td class="p-4 font-mono text-xs">{{ $r->dias_activos }} ({{ $r->hora_inicio }})</td>
                <td class="p-4 text-center"><i class="fas {{ $r->requiere_carga ? 'fa-check text-green-500' : 'fa-times text-red-500' }}"></i></td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('rutas_reparto.edit', $r->id) }}" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('rutas_reparto.destroy', $r->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')<button class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-slate-400">No hay rutas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection