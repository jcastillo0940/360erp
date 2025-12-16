@extends('layouts.app')
@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Configuración del ERP</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="border p-4 rounded">
            <h3 class="font-bold mb-4">Facturación</h3>
            <label class="block mb-2">Próximo Número Factura</label>
            <input type="text" value="FAC-0001" class="border p-2 w-full rounded">
            
            <label class="block mt-4 mb-2">Términos y Condiciones Default</label>
            <textarea class="border p-2 w-full rounded h-24">Pago a 30 días. Cheques a nombre de...</textarea>
        </div>

        <div class="border p-4 rounded">
            <h3 class="font-bold mb-4">Cuentas Predeterminadas</h3>
            <label class="block mb-2">Cuenta Ventas Default</label>
            <select class="border p-2 w-full rounded"><option>4.1.01 Ventas Generales</option></select>

            <label class="block mt-4 mb-2">Cuenta Cuentas por Cobrar</label>
            <select class="border p-2 w-full rounded"><option>1.1.03 Clientes Nacionales</option></select>
        </div>
    </div>
    
    <button class="mt-6 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Guardar Cambios</button>
</div>
@endsection