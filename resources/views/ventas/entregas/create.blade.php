@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto" x-data="ordenEntrega()">
        <div class="bg-white rounded-lg shadow-lg border border-slate-200">

            <div class="p-6 border-b bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Nueva Orden de Entrega</h2>
                    <p class="text-sm text-slate-500">Documento de logística y asignación de ruta.</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs font-bold uppercase text-slate-400">Fecha</span>
                    <span class="font-mono text-lg font-bold" x-text="fecha_emision"></span>
                </div>
            </div>

            <div class="p-8">
                <form @submit.prevent="save()" x-ref="form">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 bg-blue-50 p-4 rounded border border-blue-100">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">1. Cliente</label>
                            <select x-model="cliente_id" @change="loadClientData()"
                                class="w-full border-slate-300 rounded shadow-sm focus:ring-blue-500" required>
                                <option value="">Seleccione Cliente...</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}" data-lista="{{ $c->lista_precio_id }}"> {{ $c->razon_social }}
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
                            <label class="block text-sm font-bold text-slate-700 mb-1">Fecha de Entrega Estimada</label>
                            <input type="date" x-model="fecha_entrega" class="w-full border-slate-300 rounded shadow-sm"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ruta de Reparto</label>
                            <select x-model="ruta_reparto_id" class="w-full border-slate-300 rounded shadow-sm">
                                <option value="">No Asignar Ruta</option>
                                @foreach($rutas as $r)
                                    <option value="{{ $r->id }}">{{ $r->nombre }} ({{ $r->repartidor->nombre ?? 'Sin Chofer' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">N° O.C. Cliente</label>
                            <input type="text" x-model="oc_externa" maxlength="10" placeholder="Ej: 4500123456"
                                class="w-full border-slate-300 rounded shadow-sm font-mono uppercase focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Observaciones</label>
                            <input type="text" x-model="observaciones" class="w-full border-slate-300 rounded shadow-sm">
                        </div>
                    </div>

                    <h3 class="font-bold text-lg text-slate-700 mt-8 mb-4">Detalles del Pedido</h3>

                    <div class="mb-6 border rounded overflow-visible" style="min-height: 200px;">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-100 font-bold text-slate-600">
                                <tr>
                                    <th class="p-3 w-1/2">Producto</th>
                                    <th class="p-3 w-24 text-right">Cant.</th>
                                    <th class="p-3 w-32 text-right">Precio Unit.</th>
                                    <th class="p-3 w-32 text-right">Total Línea</th>
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
                        <button type="button" @click="addRow()"
                            class="m-2 text-blue-600 text-sm font-bold flex items-center gap-1 hover:text-blue-800 transition-colors px-3 py-2 bg-blue-50 rounded">
                            <i class="fas fa-plus-circle"></i> Agregar Producto
                        </button>
                    </div>

                    <div class="flex justify-end mb-6">
                        <div class="w-72 bg-slate-50 p-4 rounded-lg border border-slate-200 shadow-sm">
                            <div
                                class="flex justify-between border-t border-slate-300 pt-3 mt-2 font-bold text-xl text-slate-800">
                                <span>TOTAL GENERAL:</span> <span>B/. <span
                                        x-text="totalGeneral().toFixed(2)"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t gap-3">
                        <a href="{{ route('entregas.index') }}"
                            class="px-6 py-3 border rounded-lg text-slate-600 hover:bg-slate-50 font-medium">Cancelar</a>
                        <button type="submit"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg shadow-lg font-bold hover:bg-green-700 transform transition active:scale-95 flex items-center gap-2">
                            <i class="fas fa-truck"></i> Crear Orden de Entrega
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function ordenEntrega() {
            // FIX: Usar la URL base para concatenar el ID dinámicamente
            const apiRoute = '{{ route("entregas.api.searchItems") }}';
            const clientBaseRoute = '{{ url("api/entregas/data") }}';

            return {
                cliente_id: '',
                sucursal_id: '',
                lista_precio_id: '',
                fecha_emision: new Date().toISOString().split('T')[0],
                fecha_entrega: new Date().toISOString().split('T')[0],
                ruta_reparto_id: '',
                oc_externa: '',
                observaciones: '',
                sucursales: [],
                rows: [{ item_id: '', descripcion: '', cantidad: 1, precio: 0, tasa_itbms: 0, results: [], showResults: false }],

                loadClientData() {
                    // Obtener el ID directamente del modelo, o del select si es necesario
                    if (!this.cliente_id) return;

                    fetch(`${clientBaseRoute}/${this.cliente_id}`)
                        .then(res => res.json())
                        .then(data => {
                            this.sucursales = data.sucursales || [];
                            this.lista_precio_id = data.lista_precio_id;
                            this.sucursal_id = ''; // Reset sucursal on client change

                            // Si solo hay una sucursal, seleccionarla automáticamente
                            if (this.sucursales.length === 1) {
                                this.sucursal_id = this.sucursales[0].id;
                            }
                        })
                        .catch(err => console.error("Error cargando cliente:", err));
                },

                addRow() {
                    this.rows.push({ item_id: '', descripcion: '', cantidad: 1, precio: 0, tasa_itbms: 0, results: [], showResults: false });
                },
                removeRow(i) { if (this.rows.length > 1) this.rows.splice(i, 1); },

                searchProduct(index) {
                    let row = this.rows[index];
                    if (!this.cliente_id || row.descripcion.length < 2) { row.showResults = false; return; }
                    let lista = this.lista_precio_id || '';

                    fetch(apiRoute + `?q=${row.descripcion}&lista_precio_id=${lista}`)
                        .then(res => res.json())
                        .then(data => { row.results = data; row.showResults = true; })
                        .catch(err => {
                            console.error("API Error:", err);
                            row.results = [{ id: 0, nombre: "Error de búsqueda o sin resultados" }];
                            row.showResults = true;
                        });
                },

                selectProduct(index, item) {
                    if (item.id === 0) {
                        this.rows[index].showResults = false;
                        return;
                    }
                    let row = this.rows[index];
                    row.item_id = item.id;
                    row.descripcion = item.nombre;
                    row.precio = item.precio;
                    row.tasa_itbms = item.tasa_itbms;
                    row.showResults = false;
                },

                subtotalBase() {
                    return this.rows.reduce((sum, r) => sum + (r.cantidad * r.precio), 0);
                },

                itbmsTotal() {
                    return this.rows.reduce((sum, r) => {
                        let subtotal_linea = r.cantidad * r.precio;
                        return sum + (subtotal_linea * (r.tasa_itbms / 100));
                    }, 0);
                },

                totalGeneral() {
                    return this.subtotalBase() + this.itbmsTotal();
                },

                async save() {
                    if (!this.cliente_id) return Swal.fire('Falta Cliente', 'Seleccione un cliente para continuar.', 'warning');
                    if (this.subtotalBase() <= 0) return Swal.fire('Orden Vacía', 'Agregue productos a la orden.', 'warning');

                    let data = {
                        cliente_id: this.cliente_id,
                        sucursal_id: this.sucursal_id,
                        fecha_emision: this.fecha_emision,
                        fecha_entrega: this.fecha_entrega,
                        ruta_reparto_id: this.ruta_reparto_id,
                        oc_externa: this.oc_externa,
                        observaciones: this.observaciones,
                        total_general: this.totalGeneral(),
                        rows: this.rows.filter(r => r.item_id && r.cantidad > 0)
                    };

                    try {
                        let response = await fetch('{{ route("entregas.store") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify(data)
                        });
                        let result = await response.json();
                        if (result.success) {
                            Swal.fire('Orden Registrada', 'Orden de Entrega creada exitosamente', 'success').then(() => window.location.href = '/entregas');
                        } else {
                            Swal.fire('Error', result.msg, 'error');
                        }
                    } catch (e) { console.error(e); Swal.fire('Error', 'Error de conexión o servidor al guardar', 'error'); }
                }
            }
        }
    </script>
@endsection