@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Gestión de Bodegas</h2>
        <form action="{{ route('bodegas.store') }}" method="POST" class="flex gap-4 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre Bodega</label>
                <input type="text" name="nombre" class="w-full border-slate-300 rounded-lg" placeholder="Ej: Sucursal Centro" required>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Ubicación</label>
                <input type="text" name="ubicacion" class="w-full border-slate-300 rounded-lg" placeholder="Ciudad / Dirección">
            </div>
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Crear</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="p-4 font-bold text-slate-600">Nombre</th>
                    <th class="p-4 font-bold text-slate-600">Ubicación</th>
                    <th class="p-4 font-bold text-slate-600 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($bodegas as $b)
                <tr>
                    <td class="p-4">{{ $b->nombre }} @if($b->es_principal) <span class="bg-blue-100 text-blue-800 text-xs px-2 rounded">Principal</span> @endif</td>
                    <td class="p-4">{{ $b->ubicacion ?? '-' }}</td>
                    <td class="p-4 text-right">
                        @if(!$b->es_principal)
                        <form action="{{ route('bodegas.destroy', $b->id) }}" method="POST" onsubmit="return confirm('¿Eliminar bodega?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection