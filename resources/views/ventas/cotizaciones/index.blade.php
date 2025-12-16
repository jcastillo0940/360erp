@extends('layouts.app')
@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Cotizaciones</h1>
        <a href="/cotizaciones/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nueva Cotización</a>
    </div>
    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left font-bold text-gray-600">Número</th>
                    <th class="px-5 py-3 text-left font-bold text-gray-600">Cliente</th>
                    <th class="px-5 py-3 text-left font-bold text-gray-600">Total</th>
                    <th class="px-5 py-3 text-left font-bold text-gray-600">Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cotizaciones as $cot)
                <tr class="border-b">
                    <td class="px-5 py-4 bg-white text-sm">{{ $cot->numero_cotizacion }}</td>
                    <td class="px-5 py-4 bg-white text-sm">{{ $cot->cliente->razon_social }}</td>
                    <td class="px-5 py-4 bg-white text-sm font-bold">B/. {{ number_format($cot->total, 2) }}</td>
                    <td class="px-5 py-4 bg-white text-sm">{{ ucfirst($cot->estado) }}</td>
                    <td class="px-5 py-4 bg-white text-sm text-right">
                        @if($cot->estado != 'convertida')
                        <form action="{{ route('cotizaciones.convertir', $cot->id) }}" method="POST">
                            @csrf
                            <button class="text-indigo-600 font-bold hover:text-indigo-900">Convertir a Factura</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $cotizaciones->links() }}</div>
    </div>
</div>
@endsection