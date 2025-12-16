@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Editar Producto: <span class="text-blue-600">{{ $item->nombre }}</span></h2>
    <form action="{{ route('items.update', $item->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Nombre</label><input type="text" name="nombre" value="{{ $item->nombre }}" class="w-full border-slate-300 rounded" required></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Código / SKU</label><input type="text" name="codigo" value="{{ $item->codigo }}" class="w-full border-slate-300 rounded"></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Costo Unitario (B/. )</label><input type="number" name="costo_unitario" step="0.01" value="{{ $item->costo_unitario }}" class="w-full border-slate-300 rounded text-right" required></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-1">Precio de Venta Base (B/. )</label><input type="number" name="precio_venta" step="0.01" value="{{ $item->precio_venta }}" class="w-full border-slate-300 rounded text-right" required></div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Tasa ITBMS (%)</label>
                <select name="tasa_itbms" class="w-full border-slate-300 rounded font-bold">
                    <option value="7.00" {{ $item->tasa_itbms == 7.00 ? 'selected' : '' }}>7% (Normal)</option>
                    <option value="0.00" {{ $item->tasa_itbms == 0.00 ? 'selected' : '' }}>0% (Exento)</option>
                    <option value="10.00" {{ $item->tasa_itbms == 10.00 ? 'selected' : '' }}>10%</option>
                    <option value="15.00" {{ $item->tasa_itbms == 15.00 ? 'selected' : '' }}>15%</option>
                </select>
            </div>

            <div><label class="block text-sm font-bold text-slate-700 mb-1">Stock Actual</label><input type="number" name="stock" value="{{ $item->stock }}" class="w-full border-slate-300 rounded text-right bg-slate-100" readonly></div>
        </div>
        
        <div class="flex justify-between pt-6 border-t">
            <a href="{{ route('items.precios', $item->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded font-bold hover:bg-yellow-600">
                <i class="fas fa-tags mr-2"></i> Configurar Precios por Lista
            </a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection