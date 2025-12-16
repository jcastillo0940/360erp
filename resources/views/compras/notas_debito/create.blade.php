@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto" x-data="notaFlex()">
    <div class="bg-white rounded-lg shadow-lg border border-slate-200">
        <div class="p-6 border-b bg-slate-50">
            <h2 class="text-xl font-bold text-slate-800">Nueva Nota de Ajuste (Débito/Crédito)</h2>
            <p class="text-slate-500 text-sm">Registre cargos adicionales o descuentos/devoluciones.</p>
        </div>

        <div class="p-8">
            
            <div class="mb-8 flex justify-center">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="button" @click="tipo='debito'" 
                        :class="tipo=='debito' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-6 py-3 text-sm font-bold border border-gray-200 rounded-l-lg transition-colors">
                        <i class="fas fa-arrow-up mr-2"></i> CARGO (Aumentar Deuda)
                    </button>
                    <button type="button" @click="tipo='credito'" 
                        :class="tipo=='credito' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-6 py-3 text-sm font-bold border border-gray-200 rounded-r-lg transition-colors">
                        <i class="fas fa-arrow-down mr-2"></i> ABONO (Disminuir Deuda)
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">1. Proveedor</label>
                    <select x-model="proveedor_id" @change="loadFacturas()" class="w-full border-slate-300 rounded shadow-sm">
                        <option value="">-- Seleccionar --</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}">{{ $p->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">2. Factura Afectada</label>
                    <select x-model="factura_id" :disabled="!proveedor_id" class="w-full border-slate-300 rounded shadow-sm disabled:bg-gray-100">
                        <option value="">-- Seleccione --</option>
                        <template x-for="f in facturas" :key="f.id">
                            <option :value="f.id" x-text="`Fac: ${f.numero_factura_proveedor} (Saldo: B/. ${f.saldo_pendiente})`"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Fecha Emisión</label>
                    <input type="date" x-model="fecha" class="w-full border-slate-300 rounded">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Motivo / Concepto</label>
                    <select x-model="motivo" class="w-full border-slate-300 rounded">
                        <template x-if="tipo=='debito'">
                            <optgroup label="Cargos">
                                <option>Intereses por Mora</option>
                                <option>Error en Precio (Cobro Menor)</option>
                                <option>Fletes Extra</option>
                                <option>Otros Cargos</option>
                            </optgroup>
                        </template>
                        <template x-if="tipo=='credito'">
                            <optgroup label="Abonos/Descuentos">
                                <option>Devolución de Mercancía (Mermas)</option>
                                <option>Descuento por Volumen</option>
                                <option>Bonificación / Regalía</option>
                                <option>Error en Factura (Cobro Mayor)</option>
                            </optgroup>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Monto</label>
                    <input type="number" x-model="monto" step="0.01" class="w-full border-slate-300 rounded font-bold text-right text-lg" 
                           :class="tipo=='debito' ? 'text-red-600' : 'text-green-600'">
                </div>
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" x-model="aplicar_itbms" class="rounded border-gray-300 shadow-sm">
                    <span class="ml-2 text-sm text-gray-600">Aplicar ITBMS (7%)</span>
                </label>
            </div>

            <div class="p-4 rounded mb-6 text-right transition-colors duration-300" :class="tipo=='debito' ? 'bg-red-50' : 'bg-green-50'">
                <p class="text-sm font-bold opacity-60" x-text="tipo == 'debito' ? 'AUMENTARÁ LA DEUDA EN:' : 'DISMINUIRÁ LA DEUDA EN:'"></p>
                <p class="text-2xl font-bold" :class="tipo=='debito' ? 'text-red-700' : 'text-green-700'">
                    B/. <span x-text="calcularTotal().toFixed(2)"></span>
                </p>
            </div>

            <div class="flex justify-end pt-6 border-t gap-3">
                <a href="{{ route('notas_debito.index') }}" class="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50">Cancelar</a>
                <button @click="save()" class="text-white px-6 py-2 rounded shadow font-bold"
                        :class="tipo=='debito' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'">
                    <span x-text="tipo=='debito' ? 'Generar Nota de Débito' : 'Generar Nota de Crédito'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function notaFlex() {
    return {
        tipo: 'debito', // 'debito' o 'credito'
        proveedor_id: '',
        factura_id: '',
        fecha: new Date().toISOString().split('T')[0],
        motivo: 'Intereses por Mora',
        monto: '',
        aplicar_itbms: false,
        facturas: [],

        loadFacturas() {
            if(!this.proveedor_id) { this.facturas = []; return; }
            fetch('/api/notas_debito/facturas/' + this.proveedor_id)
                .then(r => r.json()).then(data => this.facturas = data);
        },

        calcularTotal() {
            let m = parseFloat(this.monto || 0);
            return this.aplicar_itbms ? m * 1.07 : m;
        },

        async save() {
            if(!this.proveedor_id || !this.factura_id || !this.monto) {
                return Swal.fire('Faltan Datos', 'Complete proveedor, factura y monto.', 'warning');
            }

            let data = {
                tipo_nota: this.tipo,
                proveedor_id: this.proveedor_id,
                factura_compra_id: this.factura_id,
                fecha_emision: this.fecha,
                motivo: this.motivo,
                monto: this.monto,
                aplicar_itbms: this.aplicar_itbms,
                observaciones: '' // Opcional
            };

            Swal.fire({title:'Procesando...', didOpen:()=>{Swal.showLoading()}});

            fetch('/notas_debito', {
                method: 'POST',
                headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                body: JSON.stringify(data)
            }).then(r=>r.json()).then(d => {
                if(d.success) {
                    Swal.fire('Éxito', 'Nota registrada correctamente.', 'success')
                        .then(() => window.location.href = '/notas_debito');
                } else {
                    Swal.fire('Error', d.msg, 'error');
                }
            });
        }
    }
}
</script>
@endsection