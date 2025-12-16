@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Editar Ruta: {{ $ruta->nombre }}</h2>
    <form action="{{ route('rutas_reparto.update', $ruta->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div><label class="block font-bold">Nombre de la Ruta</label><input type="text" name="nombre" value="{{ $ruta->nombre }}" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Repartidor Asignado</label><select name="repartidor_id" class="w-full border rounded p-2"><option value="">-- Sin asignar --</option>@foreach($repartidores as $r)<option value="{{ $r->id }}" {{ $ruta->repartidor_id == $r->id ? 'selected' : '' }}>{{ $r->nombre }}</option>@endforeach</select></div>
            <div><label class="block font-bold">Vehículo</label><input type="text" name="vehiculo" value="{{ $ruta->vehiculo }}" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Placa</label><input type="text" name="placa" value="{{ $ruta->placa }}" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Hora de Inicio</label><input type="time" name="hora_inicio" value="{{ $ruta->hora_inicio }}" class="w-full border rounded p-2" required></div>
            
            <div>
                <label class="block font-bold">Requiere Carga</label>
                <select name="requiere_carga" class="w-full border rounded p-2">
                    <option value="1" {{ $ruta->requiere_carga ? 'selected' : '' }}>Sí, necesita cargar</option>
                    <option value="0" {{ !$ruta->requiere_carga ? 'selected' : '' }}>No, está lista</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">Días Activos (Matriz Semanal)</label>
            <div class="grid grid-cols-7 gap-2">
                @php $dias_semana = ['L', 'M', 'X', 'J', 'V', 'S', 'D']; @endphp
                @foreach($dias_semana as $dia)
                <label class="p-3 border rounded text-center cursor-pointer hover:bg-blue-50">
                    <input type="checkbox" name="dias_activos[]" value="{{ $dia }}" class="mr-1"
                        {{ in_array($dia, $ruta->dias_activos) ? 'checked' : '' }}> {{ $dia }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end pt-6 mt-6 border-t"><button class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Actualizar Ruta</button></div>
    </form>
</div>
@endsection