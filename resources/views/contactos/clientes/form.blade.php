@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-slate-800">{{ isset($cliente) ? 'Editar Cliente' : 'Nuevo Cliente' }}</h2>
        </div>
        
        <form action="{{ isset($cliente) ? route('clientes.update', $cliente) : route('clientes.store') }}" method="POST">
            @csrf
            @if(isset($cliente)) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Razón Social / Nombre Completo</label>
                    <input type="text" name="razon_social" value="{{ old('razon_social', $cliente->razon_social ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Identificación / RUC</label>
                    <input type="text" name="identificacion" value="{{ old('identificacion', $cliente->identificacion ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">DV (Dígito Verificador)</label>
                    <input type="text" name="dv" value="{{ old('dv', $cliente->dv ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $cliente->email ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Dirección Física</label>
                    <textarea name="direccion" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm">{{ old('direccion', $cliente->direccion ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Condición de Pago Default</label>
                    <select name="condicion_pago" class="w-full border-slate-300 rounded-lg shadow-sm">
                        <option value="contado">Contado</option>
                        <option value="credito_30">Crédito 30 Días</option>
                        <option value="credito_60">Crédito 60 Días</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('clientes.index') }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">Guardar Cliente</button>
            </div>
        </form>
    </div>
</div>
@endsection