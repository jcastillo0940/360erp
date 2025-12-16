@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-green-600 p-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">Recibir Factura de Compra</h2>
                <p class="text-green-100 text-sm">Convirtiendo Orden #{{ $orden->numero_orden }}</p>
            </div>
            <i class="fas fa-file-invoice-dollar text-3xl opacity-50"></i>
        </div>

        <form action="{{ route('facturas_compra.store') }}" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="orden_id" value="{{ $orden->id }}">
            
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded p-4 text-sm text-blue-800">
                Está a punto de registrar la factura del proveedor <strong>{{ $orden->proveedor->razon_social }}</strong> por un monto de <strong>B/. {{ number_format($orden->total, 2) }}</strong>.
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Número de Factura (Física)</label>
                    <input type="text" name="numero_factura_proveedor" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Ej: 001-4588" required>
                    <p class="text-xs text-slate-500 mt-1">Ingrese el número que aparece en el papel/documento del proveedor.</p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Fecha de Factura</label>
                        <input type="date" name="fecha_emision" value="{{ date('Y-m-d') }}" class="w-full border-slate-300 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Condición de Pago</label>
                        <select name="condicion_pago" class="w-full border-slate-300 rounded-lg shadow-sm font-bold">
                            <option value="contado">Contado (Inmediato)</option>
                            <option value="credito_15">Crédito 15 Días</option>
                            <option value="credito_30">Crédito 30 Días</option>
                            <option value="credito_45">Crédito 45 Días</option>
                            <option value="credito_60">Crédito 60 Días</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t">
                <a href="{{ route('ordenes.show', $orden->id) }}" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Cancelar</a>
                
                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-md">
                    Registrar Factura
                </button>
            </div>
        </form>
    </div>
</div>
@endsection