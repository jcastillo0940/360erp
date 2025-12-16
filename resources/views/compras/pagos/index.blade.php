@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Historial de Pagos</h2>
            <p class="text-sm text-slate-500">Registro de egresos a proveedores</p>
        </div>
        <a href="{{ route('pagos.create') }}" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 font-bold">
            <i class="fas fa-plus"></i> Nuevo Pago
        </a>
    </div>

    <div class="p-4 border-b border-slate-100">
        <form class="flex gap-2">
            <input type="text" name="search" placeholder="Buscar por N° Pago, Referencia o Proveedor..." class="w-full max-w-md border-slate-300 rounded text-sm p-2">
            <button class="bg-slate-200 px-4 py-2 rounded text-slate-700 hover:bg-slate-300"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
            <tr>
                <th class="p-4">N° Pago</th>
                <th class="p-4">Fecha</th>
                <th class="p-4">Proveedor</th>
                <th class="p-4">Método / Ref</th>
                <th class="p-4 text-right">Monto</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($pagos as $p)
            <tr class="hover:bg-blue-50">
                <td class="p-4 font-bold text-blue-600">{{ $p->numero_pago }}</td>
                <td class="p-4">{{ $p->fecha_pago }}</td>
                <td class="p-4">{{ $p->proveedor->razon_social }}</td>
                <td class="p-4">
                    <span class="block font-bold">{{ $p->metodo_pago }}</span>
                    <span class="text-xs text-slate-500">{{ $p->referencia }}</span>
                </td>
                <td class="p-4 text-right font-bold text-green-700">B/. {{ number_format($p->monto_total, 2) }}</td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('pagos.show', $p->id) }}" class="text-blue-500 hover:text-blue-700" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                    
                    <form action="{{ route('pagos.destroy', $p->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600" title="Anular Pago"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-slate-400">No hay pagos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $pagos->links() }}</div>
</div>
@endsection