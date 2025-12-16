@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div><h1 class="text-2xl font-bold text-slate-800">Compras</h1><p class="text-slate-500">Gestión de Abastecimiento</p></div>
        <div class="flex gap-3">
            <a href="{{ route('proveedores.create') }}" class="bg-white border px-4 py-2 rounded hover:bg-gray-50">Nuevo Proveedor</a>
            <a href="{{ route('compras.ordenes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nueva Orden</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase">Proveedores</p>
            <p class="text-3xl font-bold text-slate-800">{{ $totalProveedores }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase">Órdenes Pendientes</p>
            <p class="text-3xl font-bold text-slate-800">{{ $ordenesPendientes }}</p>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-slate-50"><h3 class="font-bold text-slate-700">Últimas Órdenes</h3></div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Orden</th>
                    <th class="px-6 py-3">Proveedor</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($ultimasOrdenes as $o)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-mono font-bold text-blue-600">
                        <a href="{{ route('compras.ordenes.show', $o->id) }}">{{ $o->numero_orden }}</a>
                    </td>
                    <td class="px-6 py-4">{{ $o->proveedor->razon_social }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $o->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ strtoupper($o->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold">B/. {{ number_format($o->total, 2) }}</td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('compras.ordenes.show', $o->id) }}" class="text-blue-500 hover:text-blue-700" title="Ver / Gestionar">
                            <i class="fas fa-eye fa-lg"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $ultimasOrdenes->links() }}</div>
    </div>
</div>
@endsection