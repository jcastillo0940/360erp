@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Sucursales</h1>
                <p class="text-sm text-slate-500">Gestión de sucursales y puntos de entrega de clientes.</p>
            </div>
            <a href="{{ route('sucursales.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Nueva Sucursal
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Nombre Sucursal</th>
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Contacto</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($sucursales as $sucursal)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $sucursal->cliente->razon_social ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $sucursal->nombre }}
                            </td>
                            <td class="px-6 py-4 truncate max-w-xs" title="{{ $sucursal->direccion }}">
                                {{ Str::limit($sucursal->direccion, 50) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold">{{ $sucursal->contacto }}</span>
                                    <span class="text-xs text-slate-400">{{ $sucursal->telefono }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('sucursales.edit', $sucursal) }}"
                                        class="p-1 text-slate-400 hover:text-blue-600 transition-colors" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sucursales.destroy', $sucursal) }}" method="POST"
                                        class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-red-500 transition-colors"
                                            title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-store-slash text-3xl text-slate-300"></i>
                                    <p>No hay sucursales registradas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sucursales->links() }}
        </div>
    </div>
@endsection