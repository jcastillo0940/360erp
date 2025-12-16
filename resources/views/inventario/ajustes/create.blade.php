@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto" x-data="ajuste()">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-6 text-slate-800">Registrar Movimiento Manual</h2>
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-bold mb-1">Tipo de Movimiento</label>
                <select x-model="tipo" class="w-full border p-2 rounded bg-gray-50 font-bold" :class="tipo == 'entrada' ? 'text-green-600' : 'text-red-600'">
                    <option value="entrada">ENTRADA (Sumar Stock)</option>
                    <option value="salida">SALIDA (Restar Stock)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Fecha</label>
                <input type="date" x-model="fecha" class="w-full border p-2 rounded">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-bold mb-1">Motivo / Observación</label>
                <input type="text" x-model="motivo" class="w-full border p-2 rounded" placeholder="Ej: Conteo inicial, Merma, Regalo...">
            </div>
        </div>

        <table class="w-full mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-2">Ítem</th>
                    <th class="text-right p-2 w-32">Cantidad</th>
                    <th class="p-2 w-10"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, index) in rows" :key="index">
                    <tr class="border-b">
                        <td class="p-2">
                            <select x-model="row.id" class="w-full border p-1 rounded">
                                <option value="">Seleccione producto...</option>
                                @foreach($items as $i)
                                    <option value="{{ $i->id }}">{{ $i->codigo }} - {{ $i->nombre }} (Stock: {{ $i->stock }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-2">
                            <input type="number" x-model="row.cantidad" class="w-full border p-1 rounded text-right" placeholder="0">
                        </td>
                        <td class="p-2 text-center">
                            <button @click="removeRow(index)" class="text-red-500 font-bold">x</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <button @click="addRow()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold mb-6">+ Agregar Línea</button>

        <button @click="save()" class="w-full bg-blue-600 text-white py-3 rounded font-bold hover:bg-blue-700">PROCESAR AJUSTE</button>
    </div>
</div>

<script>
function ajuste() {
    return {
        tipo: 'entrada',
        fecha: new Date().toISOString().split('T')[0],
        motivo: '',
        rows: [{id:'', cantidad:''}],
        addRow() { this.rows.push({id:'', cantidad:''}); },
        removeRow(i) { this.rows.splice(i,1); },
        save() {
            if(this.rows.length === 0) return alert('Agregue items');
            if(!this.motivo) return alert('Ingrese un motivo');

            let data = {
                tipo: this.tipo,
                fecha: this.fecha,
                motivo: this.motivo,
                items: this.rows
            };

            fetch('/ajustes', {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify(data)
            }).then(r => r.json()).then(d => {
                if(d.success) window.location.href = '/ajustes';
                else alert('Error: ' + d.msg);
            });
        }
    }
}
</script>
@endsection