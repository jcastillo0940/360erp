@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto" x-data="pagoMultiple()">
    <div class="bg-white rounded-lg shadow-lg border border-slate-200">
        <div class="p-6 border-b bg-green-50">
            <h2 class="text-xl font-bold text-green-900"><i class="fas fa-check-circle mr-2"></i> Registrar Pago a Proveedor</h2>
            <p class="text-green-700 text-sm">Seleccione facturas para aplicar un pago total o parcial.</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">1. Seleccione Proveedor</label>
                    <select x-model="proveedor_id" @change="loadFacturas()" class="w-full border-slate-300 rounded shadow-sm">
                        <option value="">-- Seleccionar --</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}">{{ $p->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Monto Total del Pago (Cheque/Transf)</label>
                    <input type="number" x-model="monto_total" step="0.01" class="w-full border-slate-300 rounded shadow-sm text-right font-bold text-lg text-green-700">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Fecha Pago</label>
                    <input type="date" x-model="fecha_pago" class="w-full border-slate-300 rounded">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Método</label>
                    <select x-model="metodo_pago" class="w-full border-slate-300 rounded">
                        <option value="ACH">ACH / Transferencia</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta Crédito</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Referencia</label>
                    <input type="text" x-model="referencia" class="w-full border-slate-300 rounded" placeholder="N° Cheque / Confirmación">
                </div>
            </div>

            <div x-show="facturas.length > 0" class="mb-6">
                <h3 class="font-bold text-slate-700 mb-2">2. Distribuir Pago en Facturas Pendientes</h3>
                <div class="border rounded overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-100 text-slate-600 font-bold">
                            <tr>
                                <th class="p-3 w-10"><input type="checkbox"></th>
                                <th class="p-3">Factura</th>
                                <th class="p-3">Vencimiento</th>
                                <th class="p-3 text-right">Saldo Actual</th>
                                <th class="p-3 text-right w-40">Monto a Abonar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(f, index) in facturas" :key="f.id">
                                <tr class="hover:bg-green-50" :class="{'bg-green-50': f.seleccionada}">
                                    <td class="p-3 text-center">
                                        <input type="checkbox" x-model="f.seleccionada" @change="autoDistribute(index)">
                                    </td>
                                    <td class="p-3 font-bold" x-text="f.numero_factura_proveedor"></td>
                                    <td class="p-3" x-text="f.fecha_vencimiento"></td>
                                    <td class="p-3 text-right text-red-600 font-bold" x-text="'B/. ' + parseFloat(f.saldo_pendiente).toFixed(2)"></td>
                                    <td class="p-3">
                                        <input type="number" x-model="f.monto_a_pagar" :disabled="!f.seleccionada" 
                                               class="w-full border-slate-300 rounded text-right py-1 focus:ring-green-500 font-bold">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-50 font-bold">
                            <tr>
                                <td colspan="4" class="p-3 text-right">Total Asignado:</td>
                                <td class="p-3 text-right text-lg" :class="totalAsignado() > monto_total ? 'text-red-600' : 'text-green-600'">
                                    B/. <span x-text="totalAsignado().toFixed(2)"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-2 text-sm" :class="totalAsignado() != monto_total ? 'text-red-500 font-bold' : 'text-green-600'">
                    <span x-show="totalAsignado() > monto_total">⚠️ Estás asignando más dinero del que tienes en el pago total.</span>
                    <span x-show="totalAsignado() < monto_total">ℹ️ Tienes B/. <span x-text="(monto_total - totalAsignado()).toFixed(2)"></span> sin asignar.</span>
                    <span x-show="totalAsignado() == monto_total && monto_total > 0">✅ Distribución Correcta.</span>
                </div>
            </div>

            <div x-show="facturas.length === 0 && proveedor_id" class="p-8 text-center text-slate-500 bg-slate-50 rounded mb-6">
                Este proveedor no tiene facturas pendientes.
            </div>

            <div class="flex justify-end pt-6 border-t">
                <button @click="save()" class="bg-green-600 text-white px-8 py-3 rounded shadow hover:bg-green-700 font-bold text-lg">
                    Confirmar Pago
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function pagoMultiple() {
    return {
        proveedor_id: '',
        monto_total: 0,
        fecha_pago: new Date().toISOString().split('T')[0],
        metodo_pago: 'Cheque',
        referencia: '',
        facturas: [],
        
        loadFacturas() {
            if(!this.proveedor_id) { this.facturas = []; return; }
            fetch('/api/pagos/pendientes/' + this.proveedor_id)
                .then(r => r.json())
                .then(data => {
                    this.facturas = data.map(f => ({ ...f, seleccionada: false, monto_a_pagar: 0 }));
                });
        },
        
        autoDistribute(idx) {
            // Lógica simple: Si selecciona, sugiere el saldo pendiente completo o lo que quede del monto total
            let f = this.facturas[idx];
            if(f.seleccionada) {
                let disponible = this.monto_total - this.totalAsignado();
                f.monto_a_pagar = Math.min(parseFloat(f.saldo_pendiente), disponible + f.monto_a_pagar); 
                // Fix: sumar f.monto_a_pagar porque al deseleccionar se resta, pero aquí estamos seleccionando
                // Simplificación: Poner saldo total por defecto
                if(f.monto_a_pagar <= 0) f.monto_a_pagar = parseFloat(f.saldo_pendiente);
            } else {
                f.monto_a_pagar = 0;
            }
        },

        totalAsignado() {
            return this.facturas.reduce((sum, f) => sum + (f.seleccionada ? parseFloat(f.monto_a_pagar || 0) : 0), 0);
        },

        async save() {
            if(this.totalAsignado() <= 0) return Swal.fire('Error', 'Debe asignar el pago a al menos una factura.', 'warning');
            if(this.monto_total <= 0) return Swal.fire('Error', 'El monto total debe ser mayor a 0.', 'warning');
            
            // Validar tolerancia
            if(Math.abs(this.totalAsignado() - this.monto_total) > 0.01) {
                let result = await Swal.fire({
                    title: 'Montos no coinciden',
                    text: `El total del pago (B/. ${this.monto_total}) no coincide con la suma distribuida (B/. ${this.totalAsignado()}). ¿Desea continuar de todos modos?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, registrar'
                });
                if(!result.isConfirmed) return;
            }

            let data = {
                proveedor_id: this.proveedor_id,
                monto_total: this.monto_total,
                fecha_pago: this.fecha_pago,
                metodo_pago: this.metodo_pago,
                referencia: this.referencia,
                facturas: this.facturas.filter(f => f.seleccionada && f.monto_a_pagar > 0)
            };

            fetch('/pagos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify(data)
            }).then(r => r.json()).then(d => {
                if(d.success) {
                    Swal.fire('Pago Registrado', 'Las facturas han sido actualizadas.', 'success').then(() => window.location.href = '/facturas_compra');
                } else {
                    Swal.fire('Error', d.msg, 'error');
                }
            });
        }
    }
}
</script>
@endsection