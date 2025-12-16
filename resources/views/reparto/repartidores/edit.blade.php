@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Editar Repartidor: {{ $repartidor->nombre }}</h2>
    <form action="{{ route('repartidores.update', $repartidor->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div><label class="block font-bold">Nombre Completo</label><input type="text" name="nombre" value="{{ $repartidor->nombre }}" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Teléfono</label><input type="text" name="telefono" value="{{ $repartidor->telefono }}" class="w-full border rounded p-2"></div>
            <div>
                <label class="block font-bold">Estado</label>
                <select name="activo" class="w-full border rounded p-2">
                    <option value="1" {{ $repartidor->activo ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !$repartidor->activo ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </div>
        
        <h3 class="font-bold text-slate-700 mt-6 mb-3 border-t pt-4">Datos de Pago y Horario</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block font-bold">Tipo de Pago</label>
                <select name="tipo_pago" class="w-full border rounded p-2" required>
                    <option value="quincena" {{ $repartidor->tipo_pago == 'quincena' ? 'selected' : '' }}>Quincena (Salario Fijo)</option>
                    <option value="dia" {{ $repartidor->tipo_pago == 'dia' ? 'selected' : '' }}>Por Día</option>
                    <option value="hora" {{ $repartidor->tipo_pago == 'hora' ? 'selected' : '' }}>Por Hora</option>
                </select>
            </div>
            <div><label class="block font-bold">Tarifa / Salario Base</label><input type="number" name="tarifa" step="0.01" value="{{ $repartidor->tarifa }}" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Hora de Entrada</label><input type="time" name="hora_entrada" value="{{ $repartidor->hora_entrada }}" class="w-full border rounded p-2"></div>
        </div>

        <div class="flex justify-end pt-6 mt-6 border-t"><button class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Actualizar</button></div>
    </form>
</div>
@endsection