@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white rounded shadow p-8" x-data="merma()">
    <h2 class="text-2xl font-bold mb-6 text-red-700">Registrar Baja de Inventario</h2>
    <form action="{{ route('mermas.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block font-bold mb-2">Producto</label>
            <select name="item_id" x-model="item_id" @change="loadLotes()" class="w-full border p-2 rounded">
                <option value="">-- Seleccionar --</option>
                @foreach($items as $i)
                    <option value="{{ $i->id }}">{{ $i->nombre }} (Stock: {{ $i->stock }})</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-4" x-show="lotes.length > 0">
            <label class="block font-bold mb-2">Seleccionar Lote (Opcional)</label>
            <select name="lote_id" class="w-full border p-2 rounded bg-yellow-50">
                <option value="">-- Sin Lote específico --</option>
                <template x-for="l in lotes">
                    <option :value="l.id" x-text="`Lote: ${l.codigo_lote} - Vence: ${l.fecha_vencimiento} (Disp: ${l.cantidad})`"></option>
                </template>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-bold mb-2">Cantidad</label>
                <input type="number" name="cantidad" step="0.01" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold mb-2">Motivo</label>
                <select name="motivo" class="w-full border p-2 rounded">
                    <option>Vencimiento</option>
                    <option>Daño Interno</option>
                    <option>Robo / Pérdida</option>
                    <option>Daño Proveedor</option>
                </select>
            </div>
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2">Observaciones</label>
            <textarea name="observaciones" class="w-full border p-2 rounded"></textarea>
        </div>
        <button class="w-full bg-red-600 text-white py-2 rounded font-bold hover:bg-red-700">Procesar Baja</button>
    </form>
</div>
<script>
function merma(){
    return {
        item_id: '',
        lotes: [],
        loadLotes(){
            if(!this.item_id) return;
            fetch('/mermas/lotes/'+this.item_id).then(r=>r.json()).then(d=>this.lotes=d);
        }
    }
}
</script>
@endsection