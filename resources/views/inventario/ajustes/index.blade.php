@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Ajustes de Inventario</h2>
        <a href="{{ route('ajustes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Nuevo Ajuste
        </a>
    </div>
    <table class="w-full text-left text-sm text-slate-700">
        <thead class="bg-slate-100 font-bold uppercase text-xs">
            <tr>
                <th class="p-3">Código</th>
                <th class="p-3">Fecha</th>
                <th class="p-3">Tipo</th>
                <th class="p-3">Motivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ajustes as $a)
            <tr class="border-b">
                <td class="p-3 font-mono text-blue-600">{{ $a->codigo }}</td>
                <td class="p-3">{{ $a->fecha }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $a->tipo == 'entrada' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ strtoupper($a->tipo) }}
                    </span>
                </td>
                <td class="p-3 text-slate-500">{{ $a->motivo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $ajustes->links() }}</div>
</div>
@endsection