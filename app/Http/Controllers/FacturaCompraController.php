<?php
namespace App\Http\Controllers;
use App\Models\FacturaCompra;
use App\Models\OrdenCompra;
use App\Models\PagoProveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class FacturaCompraController extends Controller {
    
    public function index(Request $request) {
        $query = FacturaCompra::with('proveedor')->latest();

        // Filtros Avanzados
        if($request->filled('estado')) {
            if($request->estado == 'vencido') {
                $query->where('estado_pago', '!=', 'pagado')
                      ->where('fecha_vencimiento', '<', now());
            } else {
                $query->where('estado_pago', $request->estado);
            }
        }
        if($request->filled('fecha_inicio')) $query->whereDate('fecha_emision', '>=', $request->fecha_inicio);
        if($request->filled('fecha_fin')) $query->whereDate('fecha_emision', '<=', $request->fecha_fin);
        if($request->filled('search')) {
            $s = $request->search;
            $query->where('numero_factura_proveedor', 'LIKE', "%$s%")
                  ->orWhereHas('proveedor', function($q) use ($s){ $q->where('razon_social', 'LIKE', "%$s%"); });
        }

        $facturas = $query->paginate(10);
        return view('compras.facturas.index', compact('facturas'));
    }

    public function show($id) {
        $factura = FacturaCompra::with(['proveedor', 'ordenCompra.detalles'])->findOrFail($id);
        // Calcular pagos realizados a esta factura
        $pagos = DB::table('pago_proveedor_detalles')
                   ->join('pagos_proveedor', 'pago_proveedor_detalles.pago_proveedor_id', '=', 'pagos_proveedor.id')
                   ->where('factura_compra_id', $id)
                   ->select('pagos_proveedor.*', 'pago_proveedor_detalles.monto_aplicado')
                   ->get();
                   
        return view('compras.facturas.show', compact('factura', 'pagos'));
    }

    public function createFromOrden($ordenId) {
        $orden = OrdenCompra::with('proveedor')->findOrFail($ordenId);
        if($orden->estado == 'recibida') return redirect()->route('ordenes.index')->with('error', 'Orden ya facturada.');
        return view('compras.facturas.convertir', compact('orden'));
    }

    public function store(Request $request) {
        $request->validate(['orden_id'=>'required', 'numero_factura_proveedor'=>'required']);
        try {
            DB::transaction(function() use ($request) {
                $orden = OrdenCompra::findOrFail($request->orden_id);
                $vencimiento = Carbon::parse($request->fecha_emision);
                if(strpos($request->condicion_pago, 'credito_') !== false) {
                    $dias = (int) str_replace('credito_', '', $request->condicion_pago);
                    $vencimiento->addDays($dias);
                }

                FacturaCompra::create([
                    'numero_factura' => 'CXP-' . time(),
                    'numero_factura_proveedor' => $request->numero_factura_proveedor,
                    'proveedor_id' => $orden->proveedor_id,
                    'orden_compra_id' => $orden->id,
                    'fecha_emision' => $request->fecha_emision,
                    'fecha_vencimiento' => $vencimiento,
                    'condicion_pago' => $request->condicion_pago,
                    'total' => $orden->total,
                    'subtotal' => $orden->total / 1.07,
                    'itbms' => $orden->total - ($orden->total / 1.07),
                    'saldo_pendiente' => $orden->total,
                    'estado_pago' => 'pendiente'
                ]);
                $orden->update(['estado' => 'recibida']);
            });
            return redirect()->route('facturas_compra.index')->with('success', 'Factura registrada.');
        } catch(\Exception $e) { return back()->with('error', $e->getMessage()); }
    }

    public function destroy($id) {
        $factura = FacturaCompra::findOrFail($id);
        if($factura->saldo_pendiente < $factura->total) {
            return back()->with('error', 'No se puede eliminar una factura que ya tiene pagos aplicados.');
        }
        $factura->delete();
        return back()->with('success', 'Factura eliminada');
    }

    public function pdf($id) {
        $factura = FacturaCompra::with(['proveedor', 'ordenCompra.detalles'])->findOrFail($id);
        return view('compras.facturas.pdf', compact('factura'));
    }
}