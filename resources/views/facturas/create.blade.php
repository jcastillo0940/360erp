@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto" x-data="facturacion()">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Nueva Factura Electrónica</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Cliente</label>
                <select x-model="cliente_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seleccione Cliente...</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id }}">{{ $c->razon_social }} ({{ $c->identificacion }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Condición de Pago</label>
                <select class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    <option value="contado">Contado</option>
                    <option value="credito_30">Crédito 30 Días</option>
                    <option value="credito_60">Crédito 60 Días</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto mb-6">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Descripción</th>
                        <th class="px-4 py-2 text-right" width="100">Cant.</th>
                        <th class="px-4 py-2 text-right" width="150">Precio</th>
                        <th class="px-4 py-2 text-right" width="100">ITBMS</th>
                        <th class="px-4 py-2 text-right" width="150">Total</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                <select x-model="row.id" @change="updateRowInfo(index, $event.target)" class="w-full border rounded p-1">
                                    <option value="">Seleccionar producto...</option>
                                    @foreach($items as $i)
                                        <option value="{{ $i->id }}" data-price="{{ $i->precio_unitario }}" data-tax="{{ $i->itbms }}">{{ $i->descripcion }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2"><input type="number" x-model.number="row.cantidad" min="1" class="w-full border rounded p-1 text-right"></td>
                            <td class="px-4 py-2"><input type="number" x-model.number="row.precio" step="0.01" class="w-full border rounded p-1 text-right bg-gray-50"></td>
                            <td class="px-4 py-2 text-right" x-text="'B/. ' + calculateRowTax(row).toFixed(2)"></td>
                            <td class="px-4 py-2 text-right font-bold" x-text="'B/. ' + calculateRowTotal(row).toFixed(2)"></td>
                            <td class="px-4 py-2 text-center">
                                <button @click="removeRow(index)" class="text-red-500 hover:text-red-700 font-bold">×</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <button @click="addRow()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-6">
            + Agregar Ítem
        </button>

        <div class="flex justify-end border-t pt-4">
            <div class="w-full md:w-1/3 space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span x-text="'B/. ' + netTotal().toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>ITBMS (7%):</span>
                    <span x-text="'B/. ' + taxTotal().toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-2xl font-bold text-gray-800 mt-2 pt-2 border-t">
                    <span>TOTAL:</span>
                    <span x-text="'B/. ' + grandTotal().toFixed(2)"></span>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <button @click="saveFactura()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                EMITIR FACTURA Y FIRMAR (DGI)
            </button>
        </div>
    </div>
</div>

<script>
function facturacion() {
    return {
        cliente_id: '',
        rows: [{ id: '', precio: 0, cantidad: 1, impuesto_pct: 7 }],
        
        addRow() { 
            this.rows.push({ id: '', precio: 0, cantidad: 1, impuesto_pct: 7 }); 
        },
        removeRow(index) { 
            this.rows.splice(index, 1); 
        },
        updateRowInfo(index, selectElement) {
            const option = selectElement.options[selectElement.selectedIndex];
            this.rows[index].precio = parseFloat(option.dataset.price) || 0;
            this.rows[index].impuesto_pct = parseFloat(option.dataset.tax) || 7;
        },
        calculateRowTax(row) {
            return (row.precio * row.cantidad) * (row.impuesto_pct / 100);
        },
        calculateRowTotal(row) {
            return (row.precio * row.cantidad) + this.calculateRowTax(row);
        },
        netTotal() {
            return this.rows.reduce((sum, row) => sum + (row.precio * row.cantidad), 0);
        },
        taxTotal() {
            return this.rows.reduce((sum, row) => sum + this.calculateRowTax(row), 0);
        },
        grandTotal() {
            return this.netTotal() + this.taxTotal();
        },
        saveFactura() {
            if(!this.cliente_id) { alert('Seleccione un cliente'); return; }
            if(this.grandTotal() <= 0) { alert('Agregue items a la factura'); return; }

            const data = {
                cliente_id: this.cliente_id,
                items: this.rows,
                total_subtotal: this.netTotal(),
                total_impuesto: this.taxTotal(),
                total_pagar: this.grandTotal()
            };

            fetch('/facturas', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Factura creada exitosamente. CUFE: ' + data.cufe);
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.msg || 'Error desconocido'));
                }
            })
            .catch(err => alert('Error de conexión'));
        }
    }
}
</script>
@endsection