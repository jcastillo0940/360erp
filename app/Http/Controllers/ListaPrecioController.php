<?php
namespace App\Http\Controllers;
use App\Models\ListaPrecio;
use App\Models\ListaPrecioItem;
use App\Models\Item;
use Illuminate\Http\Request;
use DB;

class ListaPrecioController extends Controller {
    
    // PANEL PRINCIPAL
    public function index() {
        $listas = ListaPrecio::latest()->paginate(10);
        return view('configuracion.listas_precios.index', compact('listas'));
    }

    public function create() {
        return view('configuracion.listas_precios.create');
    }

    public function store(Request $request) {
        $request->validate(['nombre' => 'required|unique:listas_precios,nombre']);
        ListaPrecio::create($request->all());
        return redirect()->route('listas_precios.index')->with('success', 'Lista creada.');
    }

    public function edit($id) {
        $lista = ListaPrecio::findOrFail($id);
        return view('configuracion.listas_precios.edit', compact('lista'));
    }

    public function update(Request $request, $id) {
        $lista = ListaPrecio::findOrFail($id);
        $lista->update($request->all());
        return redirect()->route('listas_precios.index')->with('success', 'Lista actualizada.');
    }

    public function destroy($id) {
        ListaPrecio::destroy($id);
        return back()->with('success', 'Lista eliminada.');
    }
}