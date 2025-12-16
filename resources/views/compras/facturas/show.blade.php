@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded shadow p-6 mb-6">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">Factura #{{ $factura->numero_factura_proveedor }}</h2>
            <div class="text-right">
                <span class="block text-sm text-gray-500">Saldo Pendiente</span>
                <span class="text-2xl font-bold text-red-600">B/. {{ number_format($factura->saldo_pendiente, 2) }}</span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <strong>Proveedor:</strong> {{ $factura->proveedor->razon_social }}<br>
                <strong>Emisión:</strong> {{ $factura->fecha_emision }}
            </div>
            <div class="text-right">
                <strong>Total Original:</strong> B/. {{ number_format($factura->total, 2) }}<br>
                <strong>Estado:</strong> {{ strtoupper($factura->estado_pago) }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <div class="p-4 bg-slate-50 border-b font-bold text-slate-700">Historial de Pagos / Abonos</div>
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-100">
                <tr><th>Fecha</th><th>N° Pago</th><th>Método</th><th class="text-right">Monto Aplicado</th></tr>
            </thead>
            <tbody>
                @forelse($pagos as $p)
                <tr>
                    <td class="p-3">{{ $p->fecha_pago }}</td>
                    <td class="p-3">{{ $p->numero_pago }}</td>
                    <td class="p-3">{{ $p->metodo_pago }} - {{ $p->referencia }}</td>
                    <td class="p-3 text-right font-bold text-green-600">B/. {{ number_format($p->monto_aplicado, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-4 text-center text-slate-400">No hay pagos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection