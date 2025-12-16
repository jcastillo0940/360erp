<?php
namespace App\Http\Controllers;
use App\Models\Repartidor;
use Illuminate\Http\Request;

class RepartidorController extends Controller
{
    public function index()
    {
        $repartidores = Repartidor::latest()->paginate(10);
        return view('reparto.repartidores.index', compact('repartidores'));
    }
    public function create()
    {
        return view('reparto.repartidores.create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required', 'tipo_pago' => 'required']);
        Repartidor::create($request->all());
        return redirect()->route('repartidores.index')->with('success', 'Repartidor creado.');
    }
    public function edit($id)
    {
        $repartidor = Repartidor::findOrFail($id);
        return view('reparto.repartidores.edit', compact('repartidor'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required', 'tipo_pago' => 'required']);
        Repartidor::findOrFail($id)->update($request->all());
        return redirect()->route('repartidores.index')->with('success', 'Repartidor actualizado.');
    }
    public function destroy($id)
    {
        Repartidor::destroy($id);
        return back()->with('success', 'Repartidor eliminado.');
    }

    // --- DASHBOARD DE REPARTIDOR ---
    public function dashboard(Request $request)
    {
        $fecha = $request->get('fecha', now()->format('Y-m-d'));
        $repartidor_id = $request->get('repartidor_id', null);

        $repartidores = Repartidor::all();

        $query = \App\Models\OrdenEntrega::with(['cliente', 'sucursal', 'ruta.repartidor'])
            ->whereDate('fecha_entrega', $fecha)
            ->whereIn('estado', ['pendiente', 'en_ruta']);

        if ($repartidor_id) {
            $query->whereHas('ruta', function ($q) use ($repartidor_id) {
                $q->where('repartidor_id', $repartidor_id);
            });
        }

        $ordenes_asignadas = $query->orderBy('ruta_reparto_id')->get();

        return view('reparto.dashboard', compact('ordenes_asignadas', 'fecha', 'repartidores', 'repartidor_id'));
    }

    public function check($id)
    {
        $orden = \App\Models\OrdenEntrega::findOrFail($id);
        $orden->update(['estado' => 'entregado']);
        return back()->with('success', 'Orden marcada como entregada.');
    }

    public function itinerarioPdf(Request $request)
    {
        $fecha = $request->get('fecha', now()->format('Y-m-d'));
        $repartidor_id = $request->get('repartidor_id');

        $query = \App\Models\OrdenEntrega::with(['cliente', 'sucursal', 'ruta.repartidor', 'detalles'])
            ->whereDate('fecha_entrega', $fecha)
            ->whereIn('estado', ['pendiente', 'en_ruta']);

        if ($repartidor_id) {
            $query->whereHas('ruta', function ($q) use ($repartidor_id) {
                $q->where('repartidor_id', $repartidor_id);
            });
        }

        $ordenes = $query->orderBy('ruta_reparto_id')->get();
        $repartidor = $repartidor_id ? Repartidor::find($repartidor_id) : null;

        return view('reparto.itinerario_pdf', compact('ordenes', 'fecha', 'repartidor'));
    }
}