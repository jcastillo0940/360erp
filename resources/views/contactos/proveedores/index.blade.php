@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">Proveedores</h2>
        <a href="{{ route('proveedores.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700"><i class="fas fa-plus"></i> Nuevo</a>
    </div>
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
            <tr><th class="p-4">Proveedor</th><th class="p-4">RUC</th><th class="p-4">Teléfono</th><th class="p-4 text-center">Acciones</th></tr>
        </thead>
        <tbody>
            @forelse($proveedores as $p)
            <tr class="hover:bg-blue-50">
                <td class="p-4 font-bold">{{ $p->razon_social }}</td>
                <td class="p-4">{{ $p->ruc }}</td>
                <td class="p-4">{{ $p->telefono }}</td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('proveedores.edit', $p->id) }}" class="text-blue-500"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('proveedores.destroy', $p->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-8 text-center text-slate-400">Sin proveedores.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection