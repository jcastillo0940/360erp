<?php
namespace App\Http\Controllers;
use App\Models\OrdenEntrega;
use App\Models\OrdenEntregaDetalle;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\RutaReparto;
use App\Models\Item;
use Illuminate\Http\Request;
use DB;

class OrdenEntregaController extends Controller
{

    public function index()
    {
        $ordenes = OrdenEntrega::with(['cliente', 'sucursal', 'ruta'])->latest()->paginate(10);
        return view('ventas.entregas.index', compact('ordenes'));
    }

    public function create()
    {
        $clientes = Cliente::with('sucursales')->where('activo', true)->get();
        $rutas = RutaReparto::with('repartidor')->get();
        return view('ventas.entregas.create', compact('clientes', 'rutas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'fecha_entrega' => 'required|date',
            'rows' => 'required|array|min:1'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Crear Orden
                $orden = OrdenEntrega::create([
                    'numero_orden' => 'OE-' . time(),
                    'cliente_id' => $request->cliente_id,
                    'sucursal_id' => $request->sucursal_id,
                    'fecha_emision' => $request->fecha_emision ?? now(),
                    'fecha_entrega' => $request->fecha_entrega,
                    'ruta_reparto_id' => $request->ruta_reparto_id,
                    'oc_externa' => $request->oc_externa,
                    'observaciones' => $request->observaciones,
                    'estado' => 'pendiente',
                    'total' => $request->total_general
                ]);

                // 2. Crear Detalles
                foreach ($request->rows as $row) {
                    if (!$row['item_id'])
                        continue;

                    OrdenEntregaDetalle::create([
                        'orden_entrega_id' => $orden->id,
                        'item_id' => $row['item_id'],
                        'descripcion' => $row['descripcion'],
                        'cantidad' => $row['cantidad'],
                        'precio_unitario' => $row['precio'],
                        'total' => $row['cantidad'] * $row['precio']
                    ]);
                }
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
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

        $items = Item::where('activo', true)
            // ->where('stock', '>', 0) // Permitir facturar sin stock para demo
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


    public function convertir($id)
    {
        $orden = OrdenEntrega::with(['cliente', 'sucursal', 'detalles'])->findOrFail($id);

        session()->flash('orden_entrega_data', [
            'cliente_id' => $orden->cliente_id,
            'sucursal_id' => $orden->sucursal_id,
            'fecha_emision' => $orden->fecha_emision,
            'observaciones' => $orden->observaciones,
            'detalles' => $orden->detalles->map(function ($detalle) {
                return [
                    'item_id' => $detalle->item_id,
                    'descripcion' => $detalle->descripcion,
                    'cantidad' => $detalle->cantidad,
                    'precio' => $detalle->precio_unitario,
                    'tasa_itbms' => $detalle->tasa_itbms ?? 0,
                    'total' => $detalle->total
                ];
            })->toArray()
        ]);

        return redirect()->route('facturas.create');
    }

    public function edit($id)
    {
        $orden = OrdenEntrega::with(['detalles.item', 'cliente.sucursales'])->findOrFail($id);

        if ($orden->estado == 'facturado') {
            return redirect()->route('entregas.index')->with('error', 'No se puede editar una orden ya facturada.');
        }

        $clientes = Cliente::where('activo', true)->get();
        $rutas = RutaReparto::with('repartidor')->get();
        return view('ventas.entregas.edit', compact('orden', 'clientes', 'rutas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required',
            'fecha_entrega' => 'required|date',
            'rows' => 'required|array|min:1'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $orden = OrdenEntrega::findOrFail($id);

                // 1. Actualizar Cabecera
                $orden->update([
                    'cliente_id' => $request->cliente_id,
                    'sucursal_id' => $request->sucursal_id,
                    'fecha_emision' => $request->fecha_emision ?? now(),
                    'fecha_entrega' => $request->fecha_entrega,
                    'ruta_reparto_id' => $request->ruta_reparto_id,
                    'oc_externa' => $request->oc_externa,
                    'observaciones' => $request->observaciones,
                    'total' => $request->total_general
                ]);

                // 2. Recrear Detalles
                $orden->detalles()->delete();

                foreach ($request->rows as $row) {
                    if (!$row['item_id'])
                        continue;

                    OrdenEntregaDetalle::create([
                        'orden_entrega_id' => $orden->id,
                        'item_id' => $row['item_id'],
                        'descripcion' => $row['descripcion'],
                        'cantidad' => $row['cantidad'],
                        'precio_unitario' => $row['precio'],
                        'total' => $row['cantidad'] * $row['precio']
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
        $orden = OrdenEntrega::with(['cliente', 'detalles.item'])->findOrFail($id);
        return view('ventas.entregas.show', compact('orden'));
    }

    public function destroy($id)
    {
        $orden = OrdenEntrega::findOrFail($id);
        if ($orden->estado == 'facturado') {
            return back()->with('error', 'No se puede eliminar una orden ya facturada.');
        }
        $orden->detalles()->delete();
        $orden->delete();
        return back()->with('success', 'Orden eliminada correctamente.');
    }

    public function pdf($id)
    {
        $orden = OrdenEntrega::with(['cliente', 'detalles.item'])->findOrFail($id);
        return view('ventas.entregas.pdf', compact('orden'));
    }
}