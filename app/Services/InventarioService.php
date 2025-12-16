<?php
namespace App\Services;
use App\Models\Bodega;
use App\Models\Item;
use App\Models\MovimientoInventario;
use DB;

class InventarioService {
    
    public function registrarMovimiento($itemId, $bodegaId, $tipo, $cantidad, $referencia = null) {
        // 1. Registrar Historial
        MovimientoInventario::create([
            'item_id' => $itemId,
            'bodega_id' => $bodegaId,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'referencia' => $referencia
        ]);

        // 2. Actualizar Saldos
        $bodega = Bodega::find($bodegaId);
        $pivot = $bodega->items()->where('item_id', $itemId)->first();
        
        $saldoActual = $pivot ? $pivot->pivot->cantidad : 0;
        
        if ($tipo == 'entrada' || $tipo == 'ajuste_positivo') {
            $nuevoSaldo = $saldoActual + $cantidad;
        } else {
            $nuevoSaldo = $saldoActual - $cantidad;
        }

        if ($pivot) {
            $bodega->items()->updateExistingPivot($itemId, ['cantidad' => $nuevoSaldo]);
        } else {
            $bodega->items()->attach($itemId, ['cantidad' => $nuevoSaldo]);
        }
        
        // 3. Actualizar Stock Global en Item
        $item = Item::find($itemId);
        // Recalcular total de todas las bodegas
        $totalStock = DB::table('bodega_item')->where('item_id', $itemId)->sum('cantidad');
        $item->update(['stock' => $totalStock]); // Asumiendo que agregamos campo stock en parte 1
    }
}