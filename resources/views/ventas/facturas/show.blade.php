@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Factura de Venta {{ $factura->numero_factura }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('facturas.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2">Volver</a>
            <a href="{{ route('facturas.pdf', $factura->id) }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700"><i class="fas fa-print mr-2"></i> PDF</a>
        </div>
    </div>

    <div class="bg-white rounded shadow p-8 border border-slate-200">
        <div class="grid grid-cols-2 gap-6 mb-8 border-b pb-6">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase">Cliente</span>
                <span class="text-lg font-bold">{{ $factura->cliente->razon_social }}</span><br>
                <span class="text-sm text-slate-500">RUC: {{ $factura->cliente->identificacion }}</span>
            </div>
            <div class="text-right">
                <span class="block text-xs font-bold text-slate-400 uppercase">Detalles</span>
                <span class="text-sm">Fecha: <strong>{{ $factura->fecha_emision }}</strong></span><br>
                <span class="text-sm">Estado: <span class="uppercase font-bold text-green-600">{{ $factura->estado }}</span></span>
            </div>
        </div>

        <table class="w-full text-left text-sm mb-6">
            <thead class="bg-slate-50 border-b">
                <tr><th>Producto</th><th class="text-right">Cant.</th><th class="text-right">Precio</th><th class="text-right">Total</th></tr>
            </thead>
            <tbody>
                @foreach($factura->detalles as $det)
                <tr class="border-b last:border-0">
                    <td class="py-3">{{ $det->descripcion }}</td>
                    <td class="py-3 text-right">{{ $det->cantidad }}</td>
                    <td class="py-3 text-right">{{ number_format($det->precio_unitario, 2) }}</td>
                    <td class="py-3 text-right font-bold">{{ number_format($det->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end">
            <div class="w-1/3 space-y-2 text-right">
                <div class="flex justify-between text-sm"><span>Subtotal</span> <span>B/. {{ number_format($factura->subtotal, 2) }}</span></div>
                <div class="flex justify-between text-sm"><span>ITBMS</span> <span>B/. {{ number_format($factura->itbms, 2) }}</span></div>
                <div class="flex justify-between text-xl font-bold text-slate-800 border-t pt-2"><span>TOTAL</span> <span>B/. {{ number_format($factura->total, 2) }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection