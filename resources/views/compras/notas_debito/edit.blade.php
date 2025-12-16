@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-xl font-bold mb-4">Editar Nota de Débito</h2>
    <div class="bg-yellow-50 p-4 rounded mb-4 text-sm text-yellow-700 border border-yellow-200">
        <i class="fas fa-lock mr-2"></i> Por seguridad contable, no se puede modificar el monto ni la factura asociada. Si hay un error, anule la nota y cree una nueva.
    </div>
    
    <form action="{{ route('notas_debito.update', $nota->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-bold mb-1">Observaciones</label>
            <textarea name="observaciones" class="w-full border rounded p-2" rows="3">{{ $nota->observaciones }}</textarea>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('notas_debito.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
            <button class="bg-blue-600 text-white px-4 py-2 rounded font-bold">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection