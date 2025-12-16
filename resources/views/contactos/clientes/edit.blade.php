@extends('layouts.app')
@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <div class="bg-white rounded-lg shadow border border-slate-200 p-8">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-xl font-bold text-slate-800">Editar Cliente: {{ $cliente->razon_social }}</h2>
                <a href="{{ route('clientes.index') }}" class="text-slate-500 hover:text-blue-600">Volver al listado</a>
            </div>

            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Razón Social</label>
                        <input type="text" name="razon_social" value="{{ $cliente->razon_social }}"
                            class="w-full border-slate-300 rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">RUC / Cédula</label>
                        <input type="text" name="identificacion" value="{{ $cliente->identificacion }}"
                            class="w-full border-slate-300 rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Dirección Principal</label>
                        <input type="text" name="direccion" value="{{ $cliente->direccion }}"
                            class="w-full border-slate-300 rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Condición de Pago Default</label>
                        <select name="condicion_pago" class="w-full border-slate-300 rounded font-bold">
                            <option value="contado" {{ $cliente->condicion_pago == 'contado' ? 'selected' : '' }}>Contado
                            </option>
                            <option value="credito_30" {{ $cliente->condicion_pago == 'credito_30' ? 'selected' : '' }}>
                                Crédito 30 Días</option>
                            <option value="credito_60" {{ $cliente->condicion_pago == 'credito_60' ? 'selected' : '' }}>
                                Crédito 60 Días</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 bg-yellow-50 p-3 rounded border border-yellow-200">
                        <label class="block text-sm font-bold text-slate-700 mb-1">ASIGNAR LISTA DE PRECIOS</label>
                        <select name="lista_precio_id" class="w-full border-slate-300 rounded font-bold">
                            <option value="">-- PRECIO BASE (Por Defecto) --</option>
                            @foreach($listas as $lista)
                                <option value="{{ $lista->id }}" {{ $cliente->lista_precio_id == $lista->id ? 'selected' : '' }}>
                                    {{ $lista->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-yellow-800 mt-1">Este precio será cargado automáticamente en Facturas y
                            Entregas.</p>
                    </div>

                </div>
                <div class="flex justify-end">
                    <button class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Actualizar
                        Datos</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow border border-slate-200 p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Sucursales / Direcciones de Entrega</h3>
            <table class="w-full text-left text-sm mb-6">
                <thead class="bg-slate-50 uppercase text-xs text-slate-500">
                    <tr>
                        <th class="p-3">Nombre Sucursal</th>
                        <th class="p-3">Dirección</th>
                        <th class="p-3">Contacto / Tel</th>
                        <th class="p-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cliente->sucursales as $suc)
                        <tr>
                            <td class="p-3 font-bold">{{ $suc->nombre }}</td>
                            <td class="p-3">{{ $suc->direccion }}</td>
                            <td class="p-3">{{ $suc->contacto }} {{ $suc->telefono ? '(' . $suc->telefono . ')' : '' }}</td>
                            <td class="p-3 text-right">
                                <form action="{{ route('clientes.sucursales.destroy', $suc->id) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-slate-400 italic">No hay sucursales adicionales
                                registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="bg-slate-50 p-4 rounded border border-slate-200">
                <h4 class="font-bold text-sm text-slate-700 mb-3">Agregar Nueva Sucursal</h4>
                <form action="{{ route('clientes.sucursales.store', $cliente->id) }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 mb-1">Nombre</label>
                        <input type="text" name="nombre" class="w-full border-slate-300 rounded text-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 mb-1">Dirección</label>
                        <input type="text" name="direccion" class="w-full border-slate-300 rounded text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Contacto</label>
                        <input type="text" name="contacto" class="w-full border-slate-300 rounded text-sm">
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <button class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Añadir Sucursal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection