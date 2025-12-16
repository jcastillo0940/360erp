@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-lg shadow border border-slate-200">
        <div class="p-6 border-b flex justify-between items-center bg-slate-50">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Cuentas por Pagar</h2>
                <p class="text-sm text-slate-500">Gestión de facturas recibidas</p>
            </div>
            <!-- 
            <div class="flex gap-2">
                <a href="{{ route('pagos.create') }}" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 font-bold flex items-center gap-2">
                    <i class="fas fa-money-bill-wave"></i> Registrar Pago
                </a>
                <a href="{{ route('pagos.estado_cuenta') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold flex items-center gap-2">
                    <i class="fas fa-file-invoice"></i> Estado de Cuenta
                </a>
            </div>
            -->
        </div>

        <div class="p-4 bg-slate-50 border-b border-slate-100">
            <form class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="text-xs font-bold text-slate-500">Estado</label>
                    <select name="estado" class="block w-40 border-slate-300 rounded text-sm">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                        <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagados</option>
                        <option value="vencido" {{ request('estado') == 'vencido' ? 'selected' : '' }}>Vencidos</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500">Desde</label>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                        class="block border-slate-300 rounded text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500">Hasta</label>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                        class="block border-slate-300 rounded text-sm">
                </div>
                <button class="bg-slate-800 text-white px-4 py-2 rounded text-sm hover:bg-slate-900">Filtrar</button>
                <a href="{{ route('facturas_compra.index') }}" class="text-slate-500 text-sm hover:underline">Limpiar</a>
            </form>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
                <tr>
                    <th class="p-4">Factura</th>
                    <th class="p-4">Proveedor</th>
                    <th class="p-4">Emisión</th>
                    <th class="p-4">Vencimiento</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-right">Saldo</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($facturas as $f)
                    <tr class="hover:bg-blue-50">
                        <td class="p-4 font-bold">{{ $f->numero_factura_proveedor }}</td>
                        <td class="p-4">{{ $f->proveedor->razon_social }}</td>
                        <td class="p-4">{{ $f->fecha_emision }}</td>
                        <td
                            class="p-4 {{ ($f->fecha_vencimiento < now() && $f->saldo_pendiente > 0) ? 'text-red-600 font-bold' : '' }}">
                            {{ $f->fecha_vencimiento }}
                        </td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded text-xs font-bold 
                                {{ $f->estado_pago == 'pagado' ? 'bg-green-100 text-green-700' : ($f->saldo_pendiente < $f->total ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                {{ $f->saldo_pendiente == 0 ? 'PAGADO' : ($f->saldo_pendiente < $f->total ? 'PARCIAL' : 'PENDIENTE') }}
                            </span>
                        </td>
                        <td class="p-4 text-right">B/. {{ number_format($f->total, 2) }}</td>
                        <td class="p-4 text-right font-bold {{ $f->saldo_pendiente > 0 ? 'text-red-600' : 'text-green-600' }}">
                            B/. {{ number_format($f->saldo_pendiente, 2) }}
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <a href="{{ route('facturas_compra.show', $f->id) }}" class="text-blue-500 hover:text-blue-700"
                                title="Ver"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('facturas_compra.pdf', $f->id) }}" target="_blank"
                                class="text-slate-500 hover:text-red-700" title="PDF"><i class="fas fa-file-pdf"></i></a>
                            <form action="{{ route('facturas_compra.destroy', $f->id) }}" method="POST"
                                class="inline delete-form">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600" title="Eliminar"><i
                                        class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">No hay facturas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $facturas->withQueryString()->links() }}</div>
    </div>
@endsection