@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <div><h2 class="text-xl font-bold text-slate-800">Control de Mermas</h2><p class="text-sm text-slate-500">Registro de pérdidas y vencimientos</p></div>
        <a href="{{ route('mermas.create') }}" class="bg-red-600 text-white px-4 py-2 rounded shadow font-bold"><i class="fas fa-trash-alt mr-2"></i> Registrar Pérdida</a>
    </div>
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 uppercase text-xs font-bold text-slate-600">
            <tr><th>Fecha</th><th>Ítem</th><th>Motivo</th><th class="text-right">Cant.</th><th class="text-right">Costo Perdido</th><th>Acción</th></tr>
        </thead>
        <tbody>
            @foreach($mermas as $m)
            <tr class="hover:bg-red-50">
                <td class="p-4">{{ $m->fecha }}</td>
                <td class="p-4">
                    <span class="font-bold">{{ $m->item->nombre }}</span>
                    @if($m->lote)<br><span class="text-xs text-slate-500">Lote: {{ $m->lote->codigo_lote }}</span>@endif
                </td>
                <td class="p-4">{{ $m->motivo }}</td>
                <td class="p-4 text-right font-bold">{{ $m->cantidad }}</td>
                <td class="p-4 text-right text-red-600 font-bold">B/. {{ number_format($m->costo_perdido, 2) }}</td>
                <td class="p-4">
                    @if(in_array($m->motivo, ['Vencimiento', 'Daño Proveedor']))
                        <a href="{{ route('notas_debito.create') }}" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded border border-green-200 hover:bg-green-200">
                            Crear Nota Crédito
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection