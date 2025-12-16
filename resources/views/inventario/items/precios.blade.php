@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-4">Precios de Venta para: <span class="text-blue-600">{{ $item->nombre }}</span></h2>
    <p class="mb-6 text-sm text-slate-500">El Precio Base actual es: B/. <strong>{{ number_format($item->precio_venta, 2) }}</strong></p>

    <form action="{{ route('items.storePrecios', $item->id) }}" method="POST">
        @csrf
        <div class="space-y-4">
            @forelse($listas as $lista)
            <div class="flex items-center justify-between border-b pb-3">
                <div>
                    <label class="block font-bold text-slate-700">{{ $lista->nombre }}</label>
                    <span class="text-xs text-slate-500">ID Lista: {{ $lista->id }}</span>
                </div>
                <div class="w-1/3">
                    <input type="number" step="0.01" name="precios[{{ $lista->id }}]" 
                           value="{{ number_format($precios[$lista->id] ?? 0, 2, '.', '') }}" 
                           placeholder="B/. 0.00" 
                           class="w-full border-slate-300 rounded text-right font-bold text-lg {{ ($precios[$lista->id] ?? 0) > 0 ? 'bg-yellow-50 border-yellow-300' : '' }}">
                </div>
            </div>
            @empty
            <div class="text-center p-6 bg-slate-50 rounded">No hay listas de precios activas. Crea una primero en Configuración.</div>
            @endforelse
        </div>
        
        <div class="flex justify-between pt-8 border-t mt-8">
            <a href="{{ route('items.index') }}" class="px-4 py-2 border rounded text-slate-600">Volver a Catálogo</a>
            <button class="bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700">Guardar Precios</button>
        </div>
    </form>
</div>
@endsection