@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Editar Lista: {{ $lista->nombre }}</h2>
    <form action="{{ route('listas_precios.update', $lista->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-bold mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ $lista->nombre }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-bold mb-1">Estado</label>
            <select name="activa" class="w-full border rounded p-2">
                <option value="1" {{ $lista->activa ? 'selected' : '' }}>Activa</option>
                <option value="0" {{ !$lista->activa ? 'selected' : '' }}>Inactiva</option>
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('listas_precios.index') }}" class="px-4 py-2 border rounded text-slate-600">Cancelar</a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection