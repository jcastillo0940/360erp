@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto" x-data="ordenCompraEdit()">

        <div
            class="bg-white rounded-t-lg shadow-sm border-x border-t border-slate-200 p-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Editar Orden de Compra {{ $orden->numero_orden }}</h2>
                <span class="text-sm text-slate-500">Modificar Pedido y Fechas</span>
            </div>
            <div class="text-right">
                <div class="text-xs font-bold text-slate-400 uppercase">Estado Actual</div>
                <div class="font-mono text-lg text-blue-600 font-bold uppercase">{{ $orden->estado }}</div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg border border-slate-200 p-8 pt-0">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 mt-6 bg-slate-50 p-6 rounded-xl border border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Proveedor <span
                            class="text-red-500">*</span></label>
                    <select x-model="proveedor_id"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all">
                        <option value="">-- Seleccione Proveedor --</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}" {{ $p->id == $orden->proveedor_id ? 'selected' : '' }}>
                                {{ $p->razon_social }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Fecha Emisión <span
                            class="text-red-500">*</span></label>
                    <input type="date" x-model="fecha"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Fecha Entrega (Est.)</label>
                    <input type="date" x-model="fecha_entrega"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Lugar de Entrega / Sucursal</label>
                    <input type="text" x-model="lugar_entrega"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500"
                        placeholder="Bodega Principal...">
                </div>
                <div class="col-span-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Observaciones</label>
                    <input type="text" x-model="observaciones"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500"
                        placeholder="Ej: Entregar en horario de oficina...">
                </div>
            </div>

            <div class="mb-6 border border-slate-200 rounded-lg overflow-visible shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-xs">
                        <tr>
                            <th class="p-4 w-1/2">Ítem / Descripción (Live Search)</th>
                            <th class="p-4 w-24 text-right">Cant.</th>
                            <th class="p-4 w-32 text-right">Costo</th>
                            <th class="p-4 w-32 text-right">Total</th>
                            <th class="p-4 w-10 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="hover:bg-blue-50/50 transition-colors align-top group">

                                <td class="p-3 relative" x-data="{ open: false, results: [] }">
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" x-model="row.descripcion" required @input.debounce.300ms="
                                                                        if(row.descripcion.length > 1) {
                                                                            fetch('{{ route('compras.api.searchItems') }}?q=' + row.descripcion)
                                                                                .then(res => res.json())
                                                                                .then(data => { results = data; open = true; })
                                                                                .catch(() => open = false);
                                                                        } else { open = false; }
                                                                   " @click.outside="open = false"
                                            placeholder="Buscar producto..."
                                            class="w-full border-slate-300 rounded-md pl-9 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div x-show="open && results.length > 0"
                                        class="absolute z-50 w-full bg-white mt-1 border border-slate-200 rounded-lg shadow-xl max-h-60 overflow-y-auto left-0 ring-1 ring-black ring-opacity-5">
                                        <ul class="py-1">
                                            <template x-for="item in results" :key="item.id">
                                                <li @click="
                                                                            row.item_id = item.id;
                                                                            row.descripcion = item.nombre;
                                                                            row.costo = item.costo_unitario;
                                                                            open = false;
                                                                        "
                                                    class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-slate-50 last:border-0 transition-colors flex justify-between items-center">
                                                    <div>
                                                        <span class="font-semibold text-slate-700 block"
                                                            x-text="item.nombre"></span>
                                                        <span class="text-xs text-slate-400 block"
                                                            x-text="item.codigo"></span>
                                                    </div>
                                                    <div class="text-right">
                                                        <span
                                                            class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded">B/.
                                                            <span x-text="item.costo_unitario"></span></span>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </td>

                                <td class="p-3">
                                    <input type="number" x-model="row.cantidad" min="1" required
                                        class="w-full border-slate-300 rounded-md text-right py-2 focus:border-blue-500 focus:ring-blue-500">
                                </td>
                                <td class="p-3">
                                    <input type="number" x-model="row.costo" step="0.01" required
                                        class="w-full border-slate-300 rounded-md text-right py-2 bg-slate-50 focus:bg-white transition-colors">
                                </td>
                                <td class="p-3 text-right font-bold text-slate-700 pt-5 text-base"
                                    x-text="'B/. ' + (row.cantidad * row.costo).toFixed(2)"></td>
                                <td class="p-3 text-center pt-4">
                                    <button @click="removeRow(index)"
                                        class="text-slate-300 hover:text-red-500 transition-colors group-hover:text-red-400">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center pt-4">
                <button @click="addRow()"
                    class="text-blue-600 font-bold text-sm flex items-center gap-2 hover:text-blue-800 transition-colors bg-blue-50 px-4 py-2 rounded-lg">
                    <i class="fas fa-plus-circle"></i> Agregar Línea
                </button>

                <div class="text-right">
                    <div class="text-sm text-slate-500 uppercase font-bold">Total Orden</div>
                    <div class="text-3xl font-bold text-slate-800 tracking-tight">B/. <span
                            x-text="grandTotal().toFixed(2)"></span></div>
                </div>
            </div>

            <div class="mt-8 border-t pt-6 flex justify-end gap-3">
                <a href="/compras"
                    class="px-6 py-3 bg-white border border-slate-300 text-slate-600 rounded-lg font-medium hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
                <button @click="update()"
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transform transition active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save"></i> Actualizar Orden
                </button>
            </div>
        </div>
    </div>

    <script>
        function ordenCompraEdit() {
            return {
                proveedor_id: '{{ $orden->proveedor_id }}',
                fecha: '{{ $orden->fecha_emision instanceof \Carbon\Carbon ? $orden->fecha_emision->format("Y-m-d") : $orden->fecha_emision }}',
                fecha_entrega: '{{ $orden->fecha_entrega instanceof \Carbon\Carbon ? $orden->fecha_entrega->format("Y-m-d") : $orden->fecha_entrega }}',
                lugar_entrega: '{{ $orden->lugar_entrega }}',
                observaciones: '{{ $orden->observaciones }}',
                rows: [
                    @foreach($orden->detalles as $detalle)
                                {
                        item_id: {{ $detalle->item_id ?? 'null' }},
                        descripcion: '{{ addslashes($detalle->descripcion) }}',
                        cantidad: {{ $detalle->cantidad }},
                        costo: {{ $detalle->costo_unitario }}
                                },
                    @endforeach
                            ],

                addRow() { this.rows.push({ item_id: null, descripcion: '', cantidad: 1, costo: 0 }); },
                removeRow(i) { if (this.rows.length > 1) this.rows.splice(i, 1); },
                grandTotal() { return this.rows.reduce((sum, row) => sum + (row.cantidad * row.costo), 0); },

                async update() {
                    // 1. VALIDACIÓN
                    if (!this.proveedor_id) {
                        return Swal.fire({ icon: 'warning', title: 'Falta Proveedor', text: 'Por favor seleccione un proveedor.' });
                    }
                    if (this.grandTotal() <= 0) {
                        return Swal.fire({ icon: 'error', title: 'Orden Vacía', text: 'El total de la orden debe ser mayor a 0.' });
                    }

                    // 2. LOADING
                    Swal.fire({ title: 'Actualizando...', text: 'Por favor espere', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

                    // 3. PREPARAR DATOS
                    let data = {
                        proveedor_id: this.proveedor_id,
                        fecha: this.fecha,
                        fecha_entrega: this.fecha_entrega,
                        observaciones: this.observaciones,
                        total_general: this.grandTotal(),
                        rows: this.rows
                    };

                    try {
                        let response = await fetch('{{ route('ordenes_compra.update', $orden->id) }}', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        });

                        let result = await response.json();

                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Actualizado!',
                                text: 'La orden se ha actualizado correctamente.',
                                confirmButtonColor: '#3b82f6'
                            }).then(() => {
                                window.location.href = '/compras';
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: result.msg || 'Error desconocido.' });
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire({ icon: 'error', title: 'Error de Red', text: 'No se pudo conectar con el servidor.' });
                    }
                }
            }
        }
    </script>
@endsection