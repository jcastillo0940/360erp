@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Centro de Reportes</h1>

    <div class="mb-8">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 border-b pb-2">Ventas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="/reportes/ventas-item" class="p-6 bg-white rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-gray-700">Ventas por Ítem</h3>
                <p class="text-sm text-gray-500 mt-2">Consulta ventas detalladas por producto/servicio.</p>
            </a>
            <div class="p-6 bg-white rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-gray-700">Ventas por Cliente</h3>
                <p class="text-sm text-gray-500 mt-2">Ranking de mejores clientes.</p>
            </div>
            <div class="p-6 bg-white rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-gray-700">Rentabilidad</h3>
                <p class="text-sm text-gray-500 mt-2">Utilidad (Precio Venta - Costo).</p>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold text-green-600 mb-4 border-b pb-2">Contables</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-gray-700">Estado de Resultados</h3>
                <p class="text-sm text-gray-500 mt-2">Ingresos vs Gastos (P&L).</p>
            </div>
            <div class="p-6 bg-white rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-gray-700">Balance General</h3>
                <p class="text-sm text-gray-500 mt-2">Activos, Pasivos y Patrimonio.</p>
            </div>
            <div class="p-6 bg-white rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-gray-700">Libro Diario</h3>
                <p class="text-sm text-gray-500 mt-2">Detalle de asientos contables.</p>
            </div>
        </div>
    </div>
</div>
@endsection