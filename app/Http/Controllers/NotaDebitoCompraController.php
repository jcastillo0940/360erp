<?php
namespace App\Http\Controllers;
use App\Models\NotaDebitoCompra;
use App\Models\FacturaCompra;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use DB;

class NotaDebitoCompraController extends Controller {
    public function index(Request $request) {
        $query = NotaDebitoCompra::with(['proveedor', 'factura'])->latest();
        if($request->filled('search')) $query->where('numero_nota', 'LIKE', "%{$request->search}%");
        $notas = $query->paginate(10);
        return view('compras.notas_debito.index', compact('notas'));
    }
    public function create() {
        $proveedores = Proveedor::whereHas('facturas')->get();
        return view('compras.notas_debito.create', compact('proveedores'));
    }
    public function store(Request $request) {
        $request->validate(['proveedor_id'=>'required', 'factura_compra_id'=>'required', 'monto'=>'required|min:0.01', 'tipo_nota'=>'required']);
        try {
            DB::transaction(function() use ($request) {
                $total = $request->monto + ($request->aplicar_itbms ? $request->monto*0.07 : 0);
                $prefijo = ($request->tipo_nota == 'debito') ? 'ND-' : 'NC-';
                
                $nota = NotaDebitoCompra::create([
                    'numero_nota' => $prefijo . time(),
                    'tipo_nota' => $request->tipo_nota,
                    'proveedor_id' => $request->proveedor_id,
                    'factura_compra_id' => $request->factura_compra_id,
                    'fecha_emision' => $request->fecha_emision,
                    'motivo' => $request->motivo,
                    'monto' => $request->monto,
                    'itbms' => ($request->aplicar_itbms ? $request->monto*0.07 : 0),
                    'total' => $total,
                    'observaciones' => $request->observaciones
                ]);

                $factura = FacturaCompra::lockForUpdate()->find($request->factura_compra_id);
                if($request->tipo_nota == 'debito') {
                    $factura->saldo_pendiente += $total;
                    if($factura->estado_pago == 'pagado') $factura->estado_pago = 'pendiente';
                } else {
                    $factura->saldo_pendiente -= $total;
                    if($factura->saldo_pendiente <= 0.01) { $factura->saldo_pendiente = 0; $factura->estado_pago = 'pagado'; }
                }
                $factura->save();
            });
            return response()->json(['success' => true]);
        } catch (\Exception $e) { return response()->json(['success'=>false, 'msg'=>$e->getMessage()]); }
    }
    public function show($id) {
        $nota = NotaDebitoCompra::with(['proveedor', 'factura'])->findOrFail($id);
        return view('compras.notas_debito.show', compact('nota'));
    }
    public function destroy($id) {
        try {
            DB::transaction(function() use ($id) {
                $nota = NotaDebitoCompra::findOrFail($id);
                $factura = FacturaCompra::find($nota->factura_compra_id);
                if($factura) {
                    // Revertir
                    if($nota->tipo_nota == 'debito') {
                        $factura->saldo_pendiente -= $nota->total;
                        if($factura->saldo_pendiente <= 0.01) { $factura->saldo_pendiente = 0; $factura->estado_pago='pagado'; }
                    } else {
                        $factura->saldo_pendiente += $nota->total;
                        $factura->estado_pago = 'pendiente';
                    }
                    $factura->save();
                }
                $nota->delete();
            });
            return back()->with('success', 'Nota anulada.');
        } catch(\Exception $e) { return back()->with('error', $e->getMessage()); }
    }
    public function pdf($id) {
        $nota = NotaDebitoCompra::with(['proveedor', 'factura'])->findOrFail($id);
        return view('compras.notas_debito.pdf', compact('nota'));
    }
    public function getFacturas($proveedor_id) {
        return response()->json(FacturaCompra::where('proveedor_id', $proveedor_id)->orderBy('fecha_emision', 'desc')->get());
    }
}