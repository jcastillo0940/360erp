<?php
namespace App\Http\Controllers;
use App\Models\PagoProveedor;
use App\Models\PagoProveedorDetalle;
use App\Models\FacturaCompra;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use DB;

class PagoProveedorController extends Controller {
    
    // ... (Métodos anteriores se mantienen igual, solo actualizamos los de reporte) ...
    
    public function index(Request $request) {
        $query = PagoProveedor::with('proveedor')->latest();
        $pagos = $query->paginate(10);
        return view('compras.pagos.index', compact('pagos'));
    }
    public function create() {
        $proveedores = Proveedor::whereHas('facturas', function($q){ $q->where('saldo_pendiente', '>', 0); })->get();
        return view('compras.pagos.create', compact('proveedores'));
    }
    public function store(Request $request) {
        // ... (Lógica de guardado estándar) ...
        try {
            DB::transaction(function() use ($request) {
                $pago = PagoProveedor::create([
                    'numero_pago' => 'PAG-' . time(),
                    'proveedor_id' => $request->proveedor_id,
                    'fecha_pago' => $request->fecha_pago,
                    'metodo_pago' => $request->metodo_pago,
                    'referencia' => $request->referencia,
                    'monto_total' => $request->monto_total,
                    'observaciones' => $request->observaciones
                ]);
                foreach($request->facturas as $f) {
                    if(!isset($f['monto_a_pagar']) || $f['monto_a_pagar'] <= 0) continue;
                    $factura = FacturaCompra::lockForUpdate()->find($f['id']);
                    $abono = min($f['monto_a_pagar'], $factura->saldo_pendiente);
                    PagoProveedorDetalle::create(['pago_proveedor_id'=>$pago->id, 'factura_compra_id'=>$factura->id, 'monto_aplicado'=>$abono]);
                    $factura->saldo_pendiente -= $abono;
                    if($factura->saldo_pendiente <= 0.01) { $factura->saldo_pendiente = 0; $factura->estado_pago = 'pagado'; }
                    else { $factura->estado_pago = 'pendiente'; }
                    $factura->save();
                }
            });
            return response()->json(['success' => true]);
        } catch (\Exception $e) { return response()->json(['success' => false, 'msg' => $e->getMessage()]); }
    }
    public function getPendientes($proveedor_id) {
        return response()->json(FacturaCompra::where('proveedor_id', $proveedor_id)->where('saldo_pendiente', '>', 0)->get());
    }
    public function show($id) {
        $pago = PagoProveedor::with(['proveedor', 'detalles.factura'])->findOrFail($id);
        return view('compras.pagos.show', compact('pago'));
    }
    public function destroy($id) {
        // ... Lógica de anulación existente ...
        PagoProveedor::destroy($id); 
        return back()->with('success', 'Pago eliminado');
    }

    // --- ESTADO DE CUENTA ACTUALIZADO (INCLUYE NOTAS DE DÉBITO) ---
    public function estadoCuenta(Request $request) {
        $proveedores = Proveedor::all();
        $movimientos = [];
        if($request->has('proveedor_id')) {
            // Traemos facturas con sus notas de débito
            $movimientos = FacturaCompra::with('notasDebito')
                ->where('proveedor_id', $request->proveedor_id)
                ->where('saldo_pendiente', '>', 0)
                ->orderBy('fecha_emision')
                ->get();
        }
        return view('compras.reportes.estado_cuenta', compact('proveedores', 'movimientos'));
    }

    public function pdfEstadoCuenta(Request $request) {
        $proveedor = Proveedor::findOrFail($request->proveedor_id);
        $movimientos = FacturaCompra::with('notasDebito')
            ->where('proveedor_id', $request->proveedor_id)
            ->where('saldo_pendiente', '>', 0)
            ->orderBy('fecha_emision')
            ->get();
            
        return view('compras.reportes.estado_cuenta_pdf', compact('proveedor', 'movimientos'));
    }
}