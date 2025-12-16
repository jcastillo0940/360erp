@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Nota de Débito {{ $nota->numero_nota }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('notas_debito.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2">Volver</a>
            <a href="{{ route('notas_debito.pdf', $nota->id) }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700"><i class="fas fa-print mr-2"></i> PDF</a>
        </div>
    </div>

    <div class="bg-white rounded shadow p-8 border border-slate-200">
        <div class="border-b pb-4 mb-4 flex justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase">Proveedor</span>
                <span class="text-lg font-bold">{{ $nota->proveedor->razon_social }}</span>
            </div>
            <div class="text-right">
                <span class="block text-xs font-bold text-slate-400 uppercase">Fecha</span>
                <span class="font-mono">{{ $nota->fecha_emision }}</span>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded mb-6 border border-slate-100">
            <h3 class="text-sm font-bold text-slate-700 mb-2">Detalles del Cargo</h3>
            <div class="flex justify-between text-sm mb-2">
                <span>Factura Afectada:</span>
                <span class="font-mono font-bold">{{ $nota->factura->numero_factura_proveedor ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
                <span>Motivo:</span>
                <span>{{ $nota->motivo }}</span>
            </div>
            @if($nota->observaciones)
            <div class="flex justify-between text-sm mb-2 text-slate-500 italic">
                <span>Nota:</span>
                <span>{{ $nota->observaciones }}</span>
            </div>
            @endif
        </div>

        <div class="flex justify-end">
            <div class="w-1/2">
                <div class="flex justify-between py-2 border-b">
                    <span>Subtotal</span>
                    <span>B/. {{ number_format($nota->monto, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span>ITBMS</span>
                    <span>B/. {{ number_format($nota->itbms, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 font-bold text-lg text-red-600">
                    <span>TOTAL CARGO</span>
                    <span>B/. {{ number_format($nota->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection