@extends('layouts.app')
@section('content')
<div class="bg-white rounded-lg shadow border border-slate-200">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Notas de Ajuste (Compras)</h2>
            <p class="text-sm text-slate-500">Historial de Cargos y Abonos</p>
        </div>
        <a href="{{ route('notas_debito.create') }}" class="bg-slate-800 text-white px-4 py-2 rounded shadow hover:bg-slate-900 font-bold">
            <i class="fas fa-plus mr-2"></i> Crear Nota
        </a>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
            <tr>
                <th class="p-4">Tipo</th>
                <th class="p-4">N° Nota</th>
                <th class="p-4">Proveedor</th>
                <th class="p-4">Factura Ref.</th>
                <th class="p-4">Motivo</th>
                <th class="p-4 text-right">Monto</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($notas as $n)
            <tr class="hover:bg-slate-50">
                <td class="p-4">
                    @if($n->tipo_nota == 'debito')
                        <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-bold">CARGO</span>
                    @else
                        <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-bold">ABONO</span>
                    @endif
                </td>
                <td class="p-4 font-bold">{{ $n->numero_nota }}</td>
                <td class="p-4">{{ $n->proveedor->razon_social }}</td>
                <td class="p-4 font-mono">{{ $n->factura->numero_factura_proveedor ?? '-' }}</td>
                <td class="p-4">{{ $n->motivo }}</td>
                <td class="p-4 text-right font-bold {{ $n->tipo_nota == 'debito' ? 'text-red-600' : 'text-green-600' }}">
                    {{ $n->tipo_nota == 'credito' ? '-' : '+' }} B/. {{ number_format($n->total, 2) }}
                </td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('notas_debito.pdf', $n->id) }}" target="_blank" class="text-slate-500 hover:text-blue-700"><i class="fas fa-file-pdf"></i></a>
                    <form action="{{ route('notas_debito.destroy', $n->id) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-8 text-center text-slate-400">Sin movimientos.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $notas->links() }}</div>
</div>
@endsection