<?php
namespace App\Http\Controllers;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Cliente;
use App\Models\Item;
use Illuminate\Http\Request;
use DB;

class CotizacionController extends Controller
{

    public function index()
    {
        $cotizaciones = Cotizacion::with('cliente')->latest()->paginate(10);
        return view('ventas.cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes = Cliente::where('activo', true)->get();
        // $items ya no es necesario pasarlo por defecto si usamos Live Search, pero lo dejo por si acaso.
        return view('ventas.cotizaciones.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'rows' => 'required|array|min:1'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $cotizacion = Cotizacion::create([
                    'numero_cotizacion' => 'COT-' . time(),
                    'cliente_id' => $request->cliente_id,
                    'fecha_emision' => $request->fecha_emision ?? now(),
                    'terminos' => $request->terminos,
                    'subtotal' => $request->subtotal,
                    'itbms' => $request->itbms,
                    'total' => $request->total
                ]);

                foreach ($request->rows as $row) {
                    if (!$row['item_id'])
                        continue;
                    CotizacionDetalle::create([
                        'cotizacion_id' => $cotizacion->id,
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

    public function pdf($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'detalles.item'])->findOrFail($id);
        return view('ventas.cotizaciones.pdf', compact('cotizacion'));
    }
}