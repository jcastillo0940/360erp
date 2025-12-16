@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Estado de Cuenta Proveedores</h2>
    <form action="{{ route('pagos.estado_cuenta.pdf') }}" method="GET" target="_blank">
        <div class="mb-4">
            <label class="block font-bold mb-2">Proveedor</label>
            <select name="proveedor_id" class="w-full border p-2 rounded" required>
                @foreach($proveedores as $p)
                    <option value="{{ $p->id }}">{{ $p->razon_social }}</option>
                @endforeach
            </select>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 w-full">
            <i class="fas fa-file-pdf mr-2"></i> Generar Reporte PDF
        </button>
    </form>
</div>
@endsection