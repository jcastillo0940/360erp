<?php
namespace App\Http\Controllers;
use App\Models\AjusteInventario;
use App\Models\AjusteDetalle;
use App\Models\Item;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use DB;

class AjusteController extends Controller {
    
    public function index() {
        $ajustes = AjusteInventario::latest()->paginate(10);
        return view('inventario.ajustes.index', compact('ajustes'));
    }

    public function create() {
        $items = Item::where('activo', true)->get();
        return view('inventario.ajustes.create', compact('items'));
    }

    public function store(Request $request) {
        $request->validate([
            'tipo' => 'required', // entrada o salida
            'items' => 'required|array',
            'fecha' => 'required|date'
        ]);

        try {
            DB::transaction(function() use ($request) {
                // 1. Crear Cabecera
                $ajuste = AjusteInventario::create([
                    'codigo' => 'AJ-' . time(),
                    'fecha' => $request->fecha,
                    'tipo' => $request->tipo,
                    'motivo' => $request->motivo
                ]);

                // 2. Procesar Items
                foreach($request->items as $row) {
                    if(!isset($row['id']) || !isset($row['cantidad']) || $row['cantidad'] <= 0) continue;

                    $item = Item::lockForUpdate()->find($row['id']); // Bloquear para evitar concurrencia
                    $stockAnterior = $item->stock;
                    
                    // Calcular nuevo stock
                    if($request->tipo == 'entrada') {
                        $item->stock += $row['cantidad'];
                    } else {
                        $item->stock -= $row['cantidad'];
                    }
                    $item->save();

                    // Guardar Detalle
                    AjusteDetalle::create([
                        'ajuste_id' => $ajuste->id,
                        'item_id' => $item->id,
                        'cantidad' => $row['cantidad']
                    ]);

                    // 3. REGISTRAR EN KARDEX (Historial)
                    MovimientoInventario::create([
                        'item_id' => $item->id,
                        'tipo' => 'ajuste_' . $request->tipo,
                        'cantidad' => $row['cantidad'],
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $item->stock,
                        'referencia' => $ajuste->codigo,
                        'nota' => $request->motivo
                    ]);
                }
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}