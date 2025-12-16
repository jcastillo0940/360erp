@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Análisis de Rentabilidad</h2>
            <p class="text-sm text-slate-500">¿Cuánto estoy ganando por producto?</p>
        </div>
        <div class="text-right">
            <span class="text-xs uppercase font-bold text-slate-400">Total Ganancia Calculada</span>
            <div class="text-2xl font-bold text-green-600">B/. {{ number_format($ventas->sum(fn($v) => $v->venta_total - $v->costo_total), 2) }}</div>
        </div>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 uppercase text-xs font-bold text-slate-600">
            <tr>
                <th class="p-4">Producto</th>
                <th class="p-4 text-right">Cant. Vendida</th>
                <th class="p-4 text-right">Venta Total</th>
                <th class="p-4 text-right">Costo Total</th>
                <th class="p-4 text-right">Utilidad Neta</th>
                <th class="p-4 text-right">Margen %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $v)
            @php 
                $utilidad = $v->venta_total - $v->costo_total;
                $margen = $v->venta_total > 0 ? ($utilidad / $v->venta_total) * 100 : 0;
            @endphp
            <tr class="border-b hover:bg-slate-50">
                <td class="p-4">
                    <span class="font-bold text-slate-700">{{ $v->nombre }}</span><br>
                    <span class="text-xs text-slate-400">{{ $v->codigo }}</span>
                </td>
                <td class="p-4 text-right">{{ $v->cantidad_vendida }}</td>
                <td class="p-4 text-right">B/. {{ number_format($v->venta_total, 2) }}</td>
                <td class="p-4 text-right text-slate-500">B/. {{ number_format($v->costo_total, 2) }}</td>
                <td class="p-4 text-right font-bold {{ $utilidad > 0 ? 'text-green-600' : 'text-red-600' }}">
                    B/. {{ number_format($utilidad, 2) }}
                </td>
                <td class="p-4 text-right">
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $margen > 30 ? 'bg-green-100 text-green-800' : ($margen > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ number_format($margen, 1) }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection