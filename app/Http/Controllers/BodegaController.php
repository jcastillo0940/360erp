<?php
namespace App\Http\Controllers;
use App\Models\Bodega;
use Illuminate\Http\Request;

class BodegaController extends Controller {
    public function index() {
        $bodegas = Bodega::all();
        return view('inventario.bodegas.index', compact('bodegas'));
    }

    public function store(Request $request) {
        Bodega::create($request->all());
        return back()->with('success', 'Bodega creada');
    }
    
    public function destroy($id) {
        Bodega::destroy($id);
        return back()->with('success', 'Bodega eliminada');
    }
}