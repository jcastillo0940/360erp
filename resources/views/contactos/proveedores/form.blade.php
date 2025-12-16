@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-slate-800">{{ isset($proveedor) ? 'Editar Proveedor' : 'Nuevo Proveedor' }}</h2>
        </div>
        
        <form action="{{ isset($proveedor) ? route('proveedores.update', $proveedor) : route('proveedores.store') }}" method="POST">
            @csrf
            @if(isset($proveedor)) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Razón Social</label>
                    <input type="text" name="razon_social" value="{{ old('razon_social', $proveedor->razon_social ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">RUC</label>
                    <input type="text" name="ruc" value="{{ old('ruc', $proveedor->ruc ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">DV</label>
                    <input type="text" name="dv" value="{{ old('dv', $proveedor->dv ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Pedidos</label>
                    <input type="email" name="email" value="{{ old('email', $proveedor->email ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $proveedor->telefono ?? '') }}" class="w-full border-slate-300 rounded-lg shadow-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Dirección</label>
                    <textarea name="direccion" rows="2" class="w-full border-slate-300 rounded-lg shadow-sm">{{ old('direccion', $proveedor->direccion ?? '') }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('proveedores.index') }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">Guardar Proveedor</button>
            </div>
        </form>
    </div>
</div>
@endsection