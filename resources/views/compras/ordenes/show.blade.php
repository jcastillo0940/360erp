@extends('layouts.app')
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Orden #{{ $orden->numero_orden }}</h1>
            <div class="flex gap-2">
                @if($orden->estado == 'pendiente' || $orden->estado == 'generada')
                    <a href="{{ route('ordenes_compra.convertir', $orden->id) }}"
                        class="bg-green-600 text-white px-4 py-2 rounded shadow">Convertir a Factura</a>
                @endif
                <a href="{{ route('ordenes_compra.pdf', $orden->id) }}" target="_blank"
                    class="bg-slate-700 text-white px-4 py-2 rounded">Imprimir</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow mb-6">
            <div class="grid grid-cols-2">
                <div>
                    <strong>Proveedor:</strong> {{ $orden->proveedor->razon_social }}<br>
                    RUC: {{ $orden->proveedor->ruc }}
                </div>
                <div class="text-right">
                    Fecha: {{ $orden->fecha_emision }}<br>
                    Estado: {{ strtoupper($orden->estado) }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-left p-4">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="p-4">Ítem</th>
                        <th class="p-4 text-right">Cant.</th>
                        <th class="p-4 text-right">Costo</th>
                        <th class="p-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orden->detalles as $d)
                        <tr>
                            <td class="p-4">{{ $d->descripcion }}</td>
                            <td class="p-4 text-right">{{ $d->cantidad }}</td>
                            <td class="p-4 text-right">{{ number_format($d->costo_unitario, 2) }}</td>
                            <td class="p-4 text-right font-bold">{{ number_format($d->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection