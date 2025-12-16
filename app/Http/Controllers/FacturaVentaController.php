<?php
namespace App\Http\Controllers;
use App\Models\FacturaVenta;
use App\Models\FacturaVentaDetalle;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\Sucursal;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use DB;

class FacturaVentaController extends Controller
{

    public function index()
    {
        $facturas = FacturaVenta::with('cliente')->latest()->paginate(10);
        return view('ventas.facturas.index', compact('facturas'));
    }

    public function create()
    {
        $clientes = Cliente::with('sucursales')->where('activo', true)->get();
        return view('ventas.facturas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'rows' => 'required|array|min:1'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Cabecera
                $factura = FacturaVenta::create([
                    'numero_factura' => $request->numero_factura ?: 'FAC-' . time(),
                    'cliente_id' => $request->cliente_id,
                    'sucursal_id' => $request->sucursal_id,
                    'fecha_emision' => $request->fecha_emision ?? now(),
                    'fecha_vencimiento' => $request->fecha_vencimiento ?? now(),
                    'condicion_pago' => $request->condicion_pago ?? 'contado',
                    'subtotal' => $request->subtotal,
                    'itbms' => $request->itbms,
                    'total' => $request->total,
                    'estado' => ($request->condicion_pago == 'contado') ? 'pagada' : 'pendiente'
                ]);

                // 2. Detalles y Movimientos
                foreach ($request->rows as $row) {
                    if (!$row['item_id'])
                        continue;

                    // Guardar detalle
                    FacturaVentaDetalle::create([
                        'factura_venta_id' => $factura->id,
                        'item_id' => $row['item_id'],
                        'descripcion' => $row['descripcion'],
                        'cantidad' => $row['cantidad'],
                        'precio_unitario' => $row['precio'],
                        'tasa_itbms' => $row['tasa_itbms'] ?? 0, // Asegurar que llegue
                        'total' => $row['cantidad'] * $row['precio']
                    ]);

                    // Descontar Stock
                    $item = Item::lockForUpdate()->find($row['item_id']);
                    $stockAnt = $item->stock;
                    $item->stock -= $row['cantidad'];
                    $item->save();

                    // Kardex
                    MovimientoInventario::create([
                        'item_id' => $item->id,
                        'bodega_id' => 1, // Bodega principal por defecto
                        'tipo' => 'salida',
                        'cantidad' => $row['cantidad'],
                        'costo_unitario' => $item->costo_unitario ?? 0,
                        'referencia' => $factura->numero_factura
                    ]);
                }
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $factura = FacturaVenta::with(['cliente', 'sucursal', 'detalles'])->findOrFail($id);
        return view('ventas.facturas.show', compact('factura'));
    }

    public function destroy($id)
    {
        try {
            $factura = FacturaVenta::findOrFail($id);

            // Restore stock for each item
            foreach ($factura->detalles as $detalle) {
                if ($detalle->item_id) {
                    $item = Item::find($detalle->item_id);
                    if ($item) {
                        $item->stock += $detalle->cantidad;
                        $item->save();
                    }
                }
            }

            // Delete details and invoice
            $factura->detalles()->delete();
            $factura->delete();

            return back()->with('success', 'Factura eliminada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function pdf($id)
    {
        $factura = FacturaVenta::with(['cliente', 'sucursal', 'detalles.item'])->findOrFail($id);
        return view('ventas.facturas.pdf', compact('factura'));
    }

    public function getData($cliente_id)
    {
        $cliente = Cliente::with('sucursales', 'listaPrecio')->findOrFail($cliente_id);
        return response()->json([
            'sucursales' => $cliente->sucursales,
            'lista_precio_id' => $cliente->lista_precio_id
        ]);
    }

    // --- LIVESEARCH ITEMS UNIFICADO ---
    public function searchItems(Request $request)
    {
        $term = $request->q;
        $listaId = $request->lista_precio_id;

        $items = \App\Models\Item::where('activo', true)
            // ->where('stock', '>', 0)
            ->where(function ($q) use ($term) {
                $q->where('nombre', 'LIKE', "%$term%")
                    ->orWhere('codigo', 'LIKE', "%$term%");
            })
            ->take(20)
            ->get();

        $resultados = $items->map(function ($item) use ($listaId) {
            $precioFinal = $item->precio_venta;

            if ($listaId) {
                $precioLista = \App\Models\ListaPrecioItem::where('lista_precio_id', $listaId)
                    ->where('item_id', $item->id)
                    ->value('precio');
                if ($precioLista) {
                    $precioFinal = $precioLista;
                }
            }

            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'codigo' => $item->codigo,
                'stock' => $item->stock,
                'precio' => $precioFinal,
                'tasa_itbms' => $item->tasa_itbms,
                'es_precio_especial' => ($precioFinal != $item->precio_venta)
            ];
        });

        return response()->json($resultados);
    }

}