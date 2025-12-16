@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto" x-data="facturaVenta()">
        <div class="bg-white rounded-lg shadow-lg border border-slate-200">

            <div class="p-6 border-b bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Nueva Venta (POS)</h2>
                    <p class="text-sm text-slate-500">Facturación inteligente con cálculo de impuestos por ítem</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs font-bold uppercase text-slate-400">Fecha</span>
                    <span class="font-mono text-lg font-bold">{{ date('d/m/Y') }}</span>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 bg-blue-50 p-4 rounded border border-blue-100">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">1. Cliente</label>
                        <select x-model="cliente_id" @change="loadClientData()"
                            class="w-full border-slate-300 rounded shadow-sm focus:ring-blue-500">
                            <option value="">Seleccione Cliente...</option>
                            @foreach($clientes as $c)
                                <option value="{{ $c->id }}" data-lista="{{ $c->lista_precio_id }}"
                                    data-sucursales="{{ json_encode($c->sucursales) }}">
                                    {{ $c->razon_social }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">2. Sucursal / Entrega</label>
                        <select x-model="sucursal_id" :disabled="!cliente_id"
                            class="w-full border-slate-300 rounded shadow-sm disabled:bg-gray-200">
                            <option value="">Dirección Principal</option>
                            <template x-for="s in sucursales" :key="s.id">
                                <option :value="s.id" x-text="s.nombre + ' - ' + s.direccion"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">N° Factura</label>
                        <input type="text" x-model="numero_factura" placeholder="FAC-AUTO"
                            class="w-full border-slate-300 rounded shadow-sm font-mono focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Fecha Emisión</label>
                        <input type="date" x-model="fecha_emision"
                            class="w-full border-slate-300 rounded shadow-sm focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6 border rounded overflow-visible" style="min-height: 200px;">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 font-bold text-slate-600">
                            <tr>
                                <th class="p-3 w-1/2">Producto</th>
                                <th class="p-3 w-24 text-right">Cant.</th>
                                <th class="p-3 w-32 text-right">Precio Unit.</th>
                                <th class="p-3 w-32 text-right">Subtotal</th>
                                <th class="p-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr class="hover:bg-slate-50 align-top">

                                    <td class="p-2 relative">
                                        <div class="relative">
                                            <input type="text" x-model="row.descripcion"
                                                @input.debounce.300ms="searchProduct(index)"
                                                placeholder="Escriba nombre o código..."
                                                class="w-full border-slate-300 rounded px-3 py-2 shadow-sm font-semibold text-slate-700"
                                                :disabled="!cliente_id" autocomplete="off">

                                            <div x-show="row.showResults && row.results.length > 0"
                                                @click.outside="row.showResults = false"
                                                class="absolute z-50 w-full bg-white mt-1 border rounded-md shadow-xl max-h-60 overflow-y-auto left-0 ring-1 ring-black ring-opacity-5">
                                                <ul>
                                                    <template x-for="item in row.results" :key="item.id">
                                                        <li @click="selectProduct(index, item)"
                                                            class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b flex justify-between group">
                                                            <div>
                                                                <span class="font-bold text-slate-700"
                                                                    x-text="item.nombre"></span>
                                                                <span class="text-xs text-slate-400 block"
                                                                    x-text="'Cód: ' + item.codigo + ' | Stock: ' + item.stock"></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="font-bold text-blue-600">B/. <span
                                                                        x-text="parseFloat(item.precio).toFixed(2)"></span></span>
                                                                <span x-show="item.tasa_itbms > 0"
                                                                    class="block text-[10px] text-red-600 font-bold uppercase">ITBMS:
                                                                    <span x-text="item.tasa_itbms + '%'"></span></span>
                                                            </div>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="p-2"><input type="number" x-model="row.cantidad" min="1"
                                            class="w-full border-slate-300 rounded text-right shadow-sm"></td>
                                    <td class="p-2">
                                        <input type="number" x-model="row.precio" step="0.01"
                                            class="w-full border-slate-300 rounded text-right shadow-sm font-mono font-bold text-slate-700">
                                        <span x-show="row.tasa_itbms > 0"
                                            class="text-[10px] text-red-600 font-bold block text-right mt-1"
                                            x-text="row.tasa_itbms + '% ITBMS'"></span>
                                    </td>

                                    <td class="p-2 text-right font-bold text-slate-700 pt-4"
                                        x-text="'B/. ' + (row.cantidad * row.precio).toFixed(2)"></td>
                                    <td class="p-2 text-center pt-3"><button @click="removeRow(index)"
                                            class="text-slate-300 hover:text-red-500"><i
                                                class="fas fa-trash-alt"></i></button></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <button @click="addRow()"
                        class="m-2 text-blue-600 text-sm font-bold flex items-center gap-1 hover:text-blue-800 transition-colors px-3 py-2 bg-blue-50 rounded">
                        <i class="fas fa-plus-circle"></i> Agregar Producto
                    </button>
                </div>

                <div class="flex justify-end mb-6">
                    <div class="w-72 bg-slate-50 p-4 rounded-lg border border-slate-200 shadow-sm">
                        <div class="flex justify-between mb-2 text-sm text-slate-600"><span>Subtotal Base:</span> <span>B/.
                                <span x-text="subtotalBase().toFixed(2)"></span></span></div>
                        <div class="flex justify-between mb-2 text-sm text-red-600"><span>ITBMS Total:</span> <span>B/.
                                <span x-text="itbmsTotal().toFixed(2)"></span></span></div>
                        <div
                            class="flex justify-between border-t border-slate-300 pt-3 mt-2 font-bold text-xl text-slate-800">
                            <span>TOTAL FINAL:</span> <span>B/. <span x-text="totalFinal().toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t gap-3">
                    <a href="{{ route('facturas.index') }}"
                        class="px-6 py-3 border rounded-lg text-slate-600 hover:bg-slate-50 font-medium">Cancelar</a>
                    <button @click="save()"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg shadow-lg font-bold hover:bg-blue-700 transform transition active:scale-95 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Procesar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function facturaVenta() {
            return {
                // ... (Variables) ...
                cliente_id: '',
                sucursal_id: '',
                lista_precio_id: '',
                numero_factura: '',
                fecha_emision: new Date().toISOString().split('T')[0],
                fecha: new Date().toISOString().split('T')[0],
                sucursales: [],
                rows: [{ item_id: '', descripcion: '', cantidad: 1, precio: 0, tasa_itbms: 0, results: [], showResults: false }],

                init() {
                    @if(session('orden_entrega_data'))
                        this.loadOrdenEntregaData();
                    @endif
                                },

                loadOrdenEntregaData() {
                    const data = @json(session('orden_entrega_data'));

                    this.cliente_id = data.cliente_id;

                    // Load client data
                    let select = document.querySelector('select[x-model="cliente_id"]');
                    if (select) {
                        let option = select.querySelector(`option[value="${this.cliente_id}"]`);
                        if (option) {
                            this.lista_precio_id = option.dataset.lista;
                            this.sucursales = JSON.parse(option.dataset.sucursales);

                            // Set sucursal after sucursales array is populated
                            setTimeout(() => {
                                this.sucursal_id = data.sucursal_id || '';
                            }, 50);
                        }
                    }

                    // Load products
                    if (data.detalles && data.detalles.length > 0) {
                        this.rows = data.detalles.map(detalle => ({
                            item_id: detalle.item_id,
                            descripcion: detalle.descripcion,
                            cantidad: detalle.cantidad,
                            precio: detalle.precio,
                            tasa_itbms: detalle.tasa_itbms,
                            results: [],
                            showResults: false
                        }));
                    }
                },

                loadClientData() {
                    let select = document.querySelector('select[x-model="cliente_id"]');
                    if (select.selectedIndex > 0) {
                        let option = select.options[select.selectedIndex];
                        this.lista_precio_id = option.dataset.lista;
                        this.sucursales = JSON.parse(option.dataset.sucursales);
                        this.sucursal_id = '';
                    } else {
                        this.sucursales = [];
                        this.sucursal_id = '';
                        this.lista_precio_id = '';
                    }
                },
                addRow() { this.rows.push({ item_id: '', descripcion: '', cantidad: 1, precio: 0, tasa_itbms: 0, results: [], showResults: false }); },
                removeRow(i) { if (this.rows.length > 1) this.rows.splice(i, 1); },

                searchProduct(index) {
                    let row = this.rows[index];
                    if (!this.cliente_id || row.descripcion.length < 2) { row.showResults = false; return; }
                    let lista = this.lista_precio_id || '';

                    fetch(`{{ route('ventas.api.searchItems') }}?q=${row.descripcion}&lista_precio_id=${lista}`)
                        .then(res => res.json())
                        .then(data => { row.results = data; row.showResults = true; })
                        .catch(err => console.error(err));
                },

                selectProduct(index, item) {
                    let row = this.rows[index];
                    row.item_id = item.id;
                    row.descripcion = item.nombre;
                    row.precio = item.precio;
                    row.tasa_itbms = item.tasa_itbms; // Capturar la tasa de impuesto
                    row.showResults = false;
                },

                // --- NUEVAS FUNCIONES DE CÁLCULO DINÁMICO ---
                subtotalBase() {
                    return this.rows.reduce((sum, r) => sum + (r.cantidad * r.precio), 0);
                },

                itbmsTotal() {
                    return this.rows.reduce((sum, r) => {
                        let subtotal_linea = r.cantidad * r.precio;
                        return sum + (subtotal_linea * (r.tasa_itbms / 100));
                    }, 0);
                },

                totalFinal() {
                    return this.subtotalBase() + this.itbmsTotal();
                },

                async save() {
                    if (!this.cliente_id) return Swal.fire('Falta Cliente', 'Seleccione un cliente para facturar.', 'warning');
                    if (this.subtotalBase() <= 0) return Swal.fire('Venta Vacía', 'Agregue productos a la venta.', 'warning');

                    // Calculate fecha_vencimiento based on condicion_pago
                    let fechaVencimiento = this.fecha_emision;
                    let condicionPago = 'contado';

                    // Get client payment condition
                    let select = document.querySelector('select[x-model="cliente_id"]');
                    if (select && select.selectedIndex > 0) {
                        let clienteOption = select.options[select.selectedIndex];
                        let clienteData = @json($clientes);
                        let cliente = clienteData.find(c => c.id == this.cliente_id);

                        if (cliente && cliente.condicion_pago) {
                            condicionPago = cliente.condicion_pago;
                            let condicion = cliente.condicion_pago.toLowerCase();
                            let dias = 0;

                            if (condicion.includes('30')) dias = 30;
                            else if (condicion.includes('60')) dias = 60;
                            else if (condicion.includes('90')) dias = 90;

                            if (dias > 0) {
                                let fecha = new Date(this.fecha_emision);
                                fecha.setDate(fecha.getDate() + dias);
                                fechaVencimiento = fecha.toISOString().split('T')[0];
                            }
                        }
                    }

                    let data = {
                        numero_factura: this.numero_factura || null,
                        cliente_id: this.cliente_id,
                        sucursal_id: this.sucursal_id,
                        fecha_emision: this.fecha_emision,
                        fecha_vencimiento: fechaVencimiento,
                        condicion_pago: condicionPago,
                        subtotal: this.subtotalBase(),
                        itbms: this.itbmsTotal(),
                        total: this.totalFinal(),
                        rows: this.rows
                    };

                    // ... (Lógica fetch) ...
                    try {
                        let response = await fetch('/facturas', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify(data)
                        });
                        let result = await response.json();
                        if (result.success) {
                            Swal.fire('Venta Registrada', 'Factura creada exitosamente', 'success').then(() => window.location.href = '/facturas');
                        } else {
                            Swal.fire('Error', result.msg, 'error');
                        }
                    } catch (e) { console.error(e); Swal.fire('Error', 'Error de conexión', 'error'); }
                }
            }
        }
    </script>
@endsection