<?php
namespace App\Http\Controllers;
use App\Models\Proveedor;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraDetalle;
use App\Models\FacturaCompra;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class CompraController extends Controller {
    
    // --- LISTADO ---
    public function index() {
        $totalProveedores = Proveedor::count();
        $ordenesPendientes = OrdenCompra::where('estado', 'pendiente')->count();
        $ultimasOrdenes = OrdenCompra::with('proveedor')->latest()->paginate(10);
        return view('compras.dashboard', compact('totalProveedores', 'ordenesPendientes', 'ultimasOrdenes'));
    }

    // --- PROVEEDORES ---
    public function proveedores() {
        $proveedores = Proveedor::latest()->paginate(10);
        return view('contactos.proveedores.index', compact('proveedores'));
    }
    
    // --- CREAR ---
    public function crearOrden() {
        $proveedores = Proveedor::where('activo', true)->get();
        return view('compras.ordenes.create', compact('proveedores'));
    }

    public function storeOrden(Request $request) {
        $request->validate(['proveedor_id' => 'required', 'rows' => 'required|array']);
        try {
            DB::transaction(function() use ($request) {
                $orden = OrdenCompra::create([
                    'numero_orden' => 'OC-' . time(),
                    'proveedor_id' => $request->proveedor_id,
                    'fecha_emision' => $request->fecha,
                    'fecha_entrega' => $request->fecha_entrega,
                    'observaciones' => $request->observaciones,
                    'total' => $request->total_general,
                    'estado' => 'pendiente'
                ]);
                foreach($request->rows as $row) {
                    if(empty($row['descripcion'])) continue;
                    OrdenCompraDetalle::create([
                        'orden_compra_id' => $orden->id,
                        'item_id' => $row['item_id'] ?? null,
                        'descripcion' => $row['descripcion'],
                        'cantidad' => $row['cantidad'],
                        'costo_unitario' => $row['costo'],
                        'total' => $row['cantidad'] * $row['costo']
                    ]);
                }
            });
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    // --- VER Y GESTIONAR ---
    public function showOrden($id) {
        $orden = OrdenCompra::with(['proveedor', 'detalles'])->findOrFail($id);
        return view('compras.ordenes.show', compact('orden'));
    }

    public function pdfOrden($id) {
        $orden = OrdenCompra::with(['proveedor', 'detalles'])->findOrFail($id);
        return view('compras.ordenes.pdf', compact('orden'));
    }

    // --- ELIMINAR (NUEVO) ---
    public function destroyOrden($id) {
        $orden = OrdenCompra::findOrFail($id);
        
        if($orden->estado != 'pendiente') {
            return back()->with('error', 'No se puede eliminar una orden procesada o recibida.');
        }

        $orden->delete(); // Borra en cascada los detalles gracias a la migración
        return redirect()->route('compras.index')->with('success', 'Orden de compra eliminada correctamente.');
    }

    // --- CONVERTIR A FACTURA ---
    public function convertirVista($id) {
        $orden = OrdenCompra::with(['proveedor', 'detalles'])->findOrFail($id);
        if($orden->estado == 'recibida') return redirect()->route('compras.index')->with('error', 'Esta orden ya fue facturada.');
        return view('compras.facturas.convertir', compact('orden'));
    }

    public function storeFactura(Request $request) {
        $request->validate(['orden_id' => 'required', 'numero_factura_proveedor' => 'required', 'fecha_emision' => 'required']);
        try {
            DB::transaction(function() use ($request) {
                $orden = OrdenCompra::findOrFail($request->orden_id);
                $vencimiento = Carbon::parse($request->fecha_emision);
                
                // Lógica de días de crédito
                if(strpos($request->condicion_pago, 'credito_') !== false) {
                    $dias = (int) str_replace('credito_', '', $request->condicion_pago);
                    $vencimiento->addDays($dias);
                }

                FacturaCompra::create([
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
            return redirect()->route('compras.index')->with('success', 'Factura registrada con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}