@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6 text-slate-800">Nuevo Cliente</h2>
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Razón Social / Nombre</label>
                <input type="text" name="razon_social" class="w-full border-slate-300 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">RUC / Cédula</label>
                <input type="text" name="identificacion" class="w-full border-slate-300 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Teléfono</label>
                <input type="text" name="telefono" class="w-full border-slate-300 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border-slate-300 rounded">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Dirección Principal</label>
                <input type="text" name="direccion" class="w-full border-slate-300 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Condición de Pago Default</label>
                <select name="condicion_pago" class="w-full border-slate-300 rounded font-bold">
                    <option value="contado">Contado</option>
                    <option value="credito_15">Crédito 15 Días</option>
                    <option value="credito_30">Crédito 30 Días</option>
                    <option value="credito_45">Crédito 45 Días</option>
                    <option value="credito_60">Crédito 60 Días</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('clientes.index') }}" class="px-4 py-2 border rounded text-slate-600">Cancelar</a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Guardar Cliente</button>
        </div>
    </form>
</div>
@endsection