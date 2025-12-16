<?php
namespace App\Http\Controllers;
use App\Models\RutaReparto;
use App\Models\Repartidor;
use Illuminate\Http\Request;

class RutaRepartoController extends Controller {
    public function index() {
        $rutas = RutaReparto::with('repartidor')->latest()->paginate(10);
        return view('reparto.rutas.index', compact('rutas'));
    }
    public function create() {
        $repartidores = Repartidor::where('activo', true)->get();
        return view('reparto.rutas.create', compact('repartidores'));
    }
    public function store(Request $request) { 
        $dias = implode(',', $request->dias_activos ?? []);
        RutaReparto::create($request->except('dias_activos') + ['dias_activos' => $dias]); 
        return redirect()->route('rutas_reparto.index')->with('success', 'Ruta creada.'); 
    }
    // VISTA DE EDICIÓN (Asegurar que los días se pasen como array para el checkbox)
    public function edit($id) {
        $ruta = RutaReparto::findOrFail($id);
        $ruta->dias_activos = explode(',', $ruta->dias_activos); // Convertir string a array para la vista
        $repartidores = Repartidor::where('activo', true)->get();
        return view('reparto.rutas.edit', compact('ruta', 'repartidores'));
    }
    public function update(Request $request, $id) { 
        $dias = implode(',', $request->dias_activos ?? []);
        RutaReparto::findOrFail($id)->update($request->except('dias_activos') + ['dias_activos' => $dias]); 
        return redirect()->route('rutas_reparto.index')->with('success', 'Ruta actualizada.'); 
    }
    public function destroy($id) { RutaReparto::destroy($id); return back()->with('success', 'Ruta eliminada.'); }
}