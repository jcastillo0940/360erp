@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Detalle de Pago: {{ $pago->numero_pago }}</h2>
        <a href="{{ route('pagos.index') }}" class="text-slate-500 hover:text-blue-600">Volver al Historial</a>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6 border border-slate-200">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Proveedor</span>
                <div class="text-lg font-bold">{{ $pago->proveedor->razon_social }}</div>
                <div class="text-sm text-slate-500">RUC: {{ $pago->proveedor->ruc }}</div>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold text-slate-400 uppercase">Total Pagado</span>
                <div class="text-2xl font-bold text-green-600">B/. {{ number_format($pago->monto_total, 2) }}</div>
                <div class="text-sm text-slate-500">{{ $pago->fecha_pago }} | {{ $pago->metodo_pago }}</div>
            </div>
        </div>
        @if($pago->observaciones)
        <div class="mt-4 pt-4 border-t">
            <span class="text-xs font-bold text-slate-400 uppercase">Observaciones</span>
            <p class="text-sm text-slate-600">{{ $pago->observaciones }}</p>
        </div>
        @endif
    </div>

    <div class="bg-white rounded shadow overflow-hidden border border-slate-200">
        <div class="p-4 bg-slate-50 border-b font-bold text-slate-700">Facturas Cubiertas por este Pago</div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-slate-500 uppercase text-xs">
                <tr><th>N° Factura</th><th>Emisión</th><th class="text-right">Monto Abonado</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($pago->detalles as $det)
                <tr>
                    <td class="p-4 font-bold">{{ $det->factura->numero_factura_proveedor ?? '---' }}</td>
                    <td class="p-4">{{ $det->factura->fecha_emision ?? '-' }}</td>
                    <td class="p-4 text-right font-bold text-green-600">B/. {{ number_format($det->monto_aplicado, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection