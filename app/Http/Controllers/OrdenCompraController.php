<?php
namespace App\Http\Controllers;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraDetalle;
use App\Models\Proveedor;
use App\Models\Item;
use App\Models\Bodega;
use Illuminate\Http\Request;
use DB;

class OrdenCompraController extends Controller
{

    public function index()
    {
        $ordenes = OrdenCompra::with('proveedor')->latest()->paginate(10);
        return view('compras.ordenes.index', compact('ordenes'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('activo', true)->get();
        $bodegas = Bodega::all();
        return view('compras.ordenes.create', compact('proveedores', 'bodegas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required',
            'fecha' => 'required|date',
            'rows' => 'required|array|min:1',
            'rows.*.descripcion' => 'required|string',
            'rows.*.cantidad' => 'required|numeric|min:0.01',
            'rows.*.costo' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Cabecera
                $orden = OrdenCompra::create([
                    'numero_orden' => 'OC-' . time(),
                    'proveedor_id' => $request->proveedor_id,
                    'fecha_emision' => $request->fecha,
                    'fecha_entrega' => $request->fecha_entrega,
                    'lugar_entrega' => $request->lugar_entrega,
                    'observaciones' => $request->observaciones,
                    'estado' => 'generada',
                    'total' => $request->total_general
                ]);

                // 2. Detalles
                foreach ($request->rows as $row) {
                    // Permitimos items sin ID (texto libre) o con ID
                    if (empty($row['descripcion']))
                        continue;

                    OrdenCompraDetalle::create([
                        'orden_compra_id' => $orden->id,
                        'item_id' => $row['item_id'] ?? null,
                        'descripcion' => $row['descripcion'],
                        'cantidad' => $row['cantidad'],
                        'costo_unitario' => $row['costo'], // En la vista es 'costo'
                        'total' => $row['cantidad'] * $row['costo']
                    ]);
                }
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function searchItems(Request $request)
    {
        $term = $request->q;

        $items = Item::where('activo', true)
            ->where(function ($q) use ($term) {
                $q->where('nombre', 'LIKE', "%$term%")
                    ->orWhere('codigo', 'LIKE', "%$term%");
            })
            ->take(20)
            ->get();

        $resultados = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'codigo' => $item->codigo,
                'stock' => $item->stock,
                'costo_unitario' => $item->costo_unitario, // Key correcta para la vista
                'precio' => $item->costo_unitario, // Fallback
                'tasa_itbms' => $item->tasa_itbms
            ];
        });

        return response()->json($resultados);
    }
    public function show($id)
    {
        $orden = OrdenCompra::with('proveedor', 'detalles')->findOrFail($id);
        return view('compras.ordenes.show', compact('orden'));
    }

    public function edit($id)
    {
        $orden = OrdenCompra::with('detalles')->findOrFail($id);
        $proveedores = Proveedor::where('activo', true)->get();
        // Chequeo de estado por seguridad
        if ($orden->estado != 'pendiente' && $orden->estado != 'generada') {
            return redirect()->route('ordenes_compra.index')->with('error', 'No se puede editar una orden procesada.');
        }
        return view('compras.ordenes.edit', compact('orden', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'proveedor_id' => 'required',
            'fecha' => 'required|date',
            'rows' => 'required|array|min:1',
            'rows.*.descripcion' => 'required|string',
            'rows.*.cantidad' => 'required|numeric|min:0.01',
            'rows.*.costo' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $orden = OrdenCompra::findOrFail($id);

                // Actualizar Cabecera
                $orden->update([
                    'proveedor_id' => $request->proveedor_id,
                    'fecha_emision' => $request->fecha,
                    'fecha_entrega' => $request->fecha_entrega, // Aquí se guarda la fecha corregida
                    'lugar_entrega' => $request->lugar_entrega,
                    'observaciones' => $request->observaciones,
                    'total' => $request->total_general
                ]);

                // Actualizar Detalles: Eliminar anteriores y crear nuevos (simple replace)
                $orden->detalles()->delete();

                foreach ($request->rows as $row) {
                    if (empty($row['descripcion']))
                        continue;

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

    public function destroy($id)
    {
        $orden = OrdenCompra::findOrFail($id);
        if ($orden->estado != 'pendiente' && $orden->estado != 'generada') {
            return back()->with('error', 'No se puede eliminar una orden procesada.');
        }
        $orden->detalles()->delete();
        $orden->delete();
        return back()->with('success', 'Orden eliminada correctamente.');
    }

    public function pdf($id)
    {
        $orden = OrdenCompra::with(['proveedor', 'detalles'])->findOrFail($id);
        return view('compras.ordenes.pdf', compact('orden'));
    }
}