<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class KardexController extends Controller {
    public function index() {
        // En una implementación real, esto consultaría la tabla movimientos_inventario
        // Aquí mostramos movimientos simulados o vacíos si no hay tabla aun
        $movimientos = []; 
        try {
            // Intenta leer de la tabla si existe
            $movimientos = DB::table('movimientos_inventario')
                ->join('items', 'movimientos_inventario.item_id', '=', 'items.id')
                ->join('bodegas', 'movimientos_inventario.bodega_id', '=', 'bodegas.id')
                ->select('movimientos_inventario.*', 'items.nombre as item', 'bodegas.nombre as bodega')
                ->latest()
                ->paginate(20);
        } catch(\Exception $e) {
            // Si falla, tabla no existe, enviamos vacío
        }
        
        return view('inventario.kardex.index', compact('movimientos'));
    }
}