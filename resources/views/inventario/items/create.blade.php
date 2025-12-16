@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Crear Nuevo Producto/Servicio</h2>
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Nombre</label><input type="text" name="nombre" class="w-full border-slate-300 rounded" required></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Código / SKU</label><input type="text" name="codigo" class="w-full border-slate-300 rounded"></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Costo Unitario (B/. )</label><input type="number" name="costo_unitario" step="0.01" class="w-full border-slate-300 rounded text-right" required value="0.00"></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Precio de Venta Base (B/. )</label><input type="number" name="precio_venta" step="0.01" class="w-full border-slate-300 rounded text-right" required value="0.00"></div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Tasa ITBMS (%)</label>
                <select name="tasa_itbms" class="w-full border-slate-300 rounded font-bold">
                    <option value="7.00">7% (Normal)</option>
                    <option value="0.00">0% (Exento)</option>
                    <option value="10.00">10%</option>
                    <option value="15.00">15%</option>
                </select>
            </div>
            
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Stock Inicial</label><input type="number" name="stock" class="w-full border-slate-300 rounded text-right" required value="0"></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Tipo</label><select name="tipo" class="w-full border-slate-300 rounded"><option value="producto">Producto Inventariable</option><option value="servicio">Servicio</option></select></div>
        </div>
        
        <div class="flex justify-end gap-3 pt-6 border-t">
            <a href="{{ route('items.index') }}" class="px-4 py-2 border rounded text-slate-600">Cancelar</a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Guardar Producto</button>
        </div>
    </form>
</div>
@endsection