@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Orden de Entrega {{ $orden->numero_orden }}</h2>
        <div class="flex gap-2">
            @if($orden->estado == 'pendiente')
                <a href="{{ route('entregas.convertir', $orden->id) }}" onclick="return confirm('¿Confirma convertir a factura?')" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 font-bold">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> FACTURAR AHORA
                </a>
            @else
                <span class="bg-gray-200 text-gray-600 px-4 py-2 rounded font-bold">FACTURADO</span>
            @endif
            <a href="{{ route('entregas.pdf', $orden->id) }}" target="_blank" class="bg-slate-700 text-white px-4 py-2 rounded">PDF</a>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <strong>Cliente:</strong> {{ $orden->cliente->razon_social }}<br>
                <strong>Sucursal:</strong> {{ $orden->sucursal->nombre ?? 'Principal' }}<br>
                <strong>Dirección:</strong> {{ $orden->sucursal->direccion ?? $orden->cliente->direccion }}
            </div>
            <div class="text-right">
                Fecha Emisión: {{ $orden->fecha_emision }}<br>
                Fecha Entrega: <strong>{{ $orden->fecha_entrega }}</strong><br>
                Estado: {{ strtoupper($orden->estado) }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left p-4">
            <thead class="bg-slate-50 border-b"><tr><th class="p-4">Ítem</th><th class="p-4 text-right">Cant.</th><th class="p-4 text-right">Precio</th><th class="p-4 text-right">Total</th></tr></thead>
            <tbody>
                @foreach($orden->detalles as $d)
                <tr>
                    <td class="p-4">{{ $d->descripcion }}</td>
                    <td class="p-4 text-right">{{ $d->cantidad }}</td>
                    <td class="p-4 text-right">{{ number_format($d->precio_unitario, 2) }}</td>
                    <td class="p-4 text-right font-bold">{{ number_format($d->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection