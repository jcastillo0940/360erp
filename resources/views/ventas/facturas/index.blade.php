@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-lg shadow border border-slate-200">
        <div class="p-6 border-b flex justify-between items-center bg-slate-50">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Facturación de Ventas</h2>
                <p class="text-sm text-slate-500">Gestión de ingresos y clientes</p>
            </div>
            <a href="{{ route('facturas.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold">
                <i class="fas fa-plus mr-2"></i> Nueva Factura
            </a>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
                <tr>
                    <th class="p-4">N° Factura</th>
                    <th class="p-4">Cliente</th>
                    <th class="p-4">Fecha</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($facturas as $f)
                    <tr class="hover:bg-blue-50">
                        <td class="p-4 font-bold text-blue-600">{{ $f->numero_factura }}</td>
                        <td class="p-4">{{ $f->cliente->razon_social }}</td>
                        <td class="p-4">{{ $f->fecha_emision }}</td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded text-xs font-bold {{ $f->estado == 'pagada' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ strtoupper($f->estado) }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-bold">B/. {{ number_format($f->total, 2) }}</td>
                        <td class="p-4 text-center space-x-2">
                            <a href="{{ route('facturas.show', $f->id) }}" class="text-blue-500 hover:text-blue-700"
                                title="Ver"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('facturas.edit', $f->id) }}" class="text-green-500 hover:text-green-700"
                                title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('facturas.pdf', $f->id) }}" target="_blank"
                                class="text-red-500 hover:text-red-700" title="PDF"><i class="fas fa-file-pdf"></i></a>
                            <form action="{{ route('facturas.destroy', $f->id) }}" method="POST" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No hay facturas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $facturas->links() }}</div>
    </div>
@endsection