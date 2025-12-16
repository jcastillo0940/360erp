@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-bold text-slate-800 mb-4">Kardex / Movimientos de Inventario</h2>
    <p class="text-slate-500 mb-6">Historial de entradas y salidas de mercancía.</p>
    
    @if(empty($movimientos) || count($movimientos) == 0)
        <div class="bg-blue-50 text-blue-700 p-4 rounded text-center">
            <i class="fas fa-info-circle mr-2"></i> No hay movimientos registrados aún o la tabla de movimientos está vacía.
            <br>Realiza una <strong>Compra</strong> o una <strong>Venta</strong> para ver movimientos aquí.
        </div>
    @else
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-100 font-bold">
                <tr>
                    <th class="p-3">Fecha</th>
                    <th class="p-3">Ítem</th>
                    <th class="p-3">Bodega</th>
                    <th class="p-3">Tipo</th>
                    <th class="p-3 text-right">Cantidad</th>
                    <th class="p-3">Referencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $m)
                <tr class="border-b">
                    <td class="p-3">{{ $m->created_at }}</td>
                    <td class="p-3">{{ $m->item }}</td>
                    <td class="p-3">{{ $m->bodega }}</td>
                    <td class="p-3 uppercase font-bold {{ $m->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">{{ $m->tipo }}</td>
                    <td class="p-3 text-right font-mono">{{ $m->cantidad }}</td>
                    <td class="p-3 text-slate-500">{{ $m->referencia }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $movimientos->links() }}</div>
    @endif
</div>
@endsection