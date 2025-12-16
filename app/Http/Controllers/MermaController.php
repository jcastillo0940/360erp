<?php
namespace App\Http\Controllers;
use App\Models\Merma;
use App\Models\Item;
use App\Models\Lote;
use Illuminate\Http\Request;
use DB;

class MermaController extends Controller {
    public function index() {
        $mermas = Merma::with('item')->latest()->paginate(10);
        return view('inventario.mermas.index', compact('mermas'));
    }
    public function create() {
        $items = Item::all();
        return view('inventario.mermas.create', compact('items'));
    }
    public function getLotes($item_id) {
        return response()->json(Lote::where('item_id', $item_id)->where('cantidad', '>', 0)->get());
    }
    public function store(Request $request) {
        $request->validate(['item_id'=>'required', 'cantidad'=>'required|numeric|min:0.01', 'motivo'=>'required']);
        try {
            DB::transaction(function() use ($request) {
                // Obtener costo
                $item = Item::findOrFail($request->item_id);
                $costo = $item->costo_unitario * $request->cantidad;
                
                // Descontar Stock Lote (Si aplica)
                if($request->lote_id) {
                    $lote = Lote::findOrFail($request->lote_id);
                    $lote->cantidad -= $request->cantidad;
                    $lote->save();
                    $costo = $lote->costo_lote * $request->cantidad; // Costo exacto del lote
                }
                
                // Descontar Stock Global
                $item->stock -= $request->cantidad;
                $item->save();

                Merma::create([
                    'codigo' => 'MER-' . time(),
                    'fecha' => now(),
                    'item_id' => $request->item_id,
                    'lote_id' => $request->lote_id,
                    'cantidad' => $request->cantidad,
                    'motivo' => $request->motivo,
                    'observaciones' => $request->observaciones,
                    'costo_perdido' => $costo
                ]);
            });
            return redirect()->route('mermas.index')->with('success', 'Merma registrada e inventario actualizado.');
        } catch(\Exception $e) { return back()->with('error', $e->getMessage()); }
    }
}