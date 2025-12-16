@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded shadow p-8" x-data="{dias: []}">
    <h2 class="text-2xl font-bold mb-6">Crear Nueva Ruta de Reparto</h2>
    <form action="{{ route('rutas_reparto.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div><label class="block font-bold">Nombre de la Ruta</label><input type="text" name="nombre" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Repartidor Asignado</label><select name="repartidor_id" class="w-full border rounded p-2"><option value="">-- Sin asignar --</option>@foreach($repartidores as $r)<option value="{{ $r->id }}">{{ $r->nombre }}</option>@endforeach</select></div>
            <div><label class="block font-bold">Vehículo (Ej: Sedan)</label><input type="text" name="vehiculo" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Placa</label><input type="text" name="placa" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Hora de Inicio</label><input type="time" name="hora_inicio" class="w-full border rounded p-2" required value="06:00"></div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">Días Activos (Códigos: L,M,X,J,V,S,D)</label>
            <div class="grid grid-cols-7 gap-2">
                @php $dias_semana = ['L', 'M', 'X', 'J', 'V', 'S', 'D']; @endphp
                @foreach($dias_semana as $dia)
                <label class="p-3 border rounded text-center cursor-pointer hover:bg-blue-50">
                    <input type="checkbox" name="dias_activos[]" value="{{ $dia }}" class="mr-1"> {{ $dia }}
                </label>
                @endforeach
            </div>
            <p class="text-xs text-slate-500 mt-2">Las órdenes de entrega con la fecha de hoy serán asignadas si coincide con este día.</p>
        </div>

        <div class="flex justify-end pt-6 mt-6 border-t"><button class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Guardar Ruta</button></div>
    </form>
</div>
@endsection