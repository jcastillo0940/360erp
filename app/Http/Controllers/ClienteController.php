<?php
namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\ListaPrecio;
use Illuminate\Http\Request;

class ClienteController extends Controller {
    
    public function index(Request $request) {
        $clientes = Cliente::with('listaPrecio')->latest()->paginate(10); // Cargar lista para el index
        return view('contactos.clientes.index', compact('clientes'));
    }

    public function create() {
        $listas = ListaPrecio::where('activa', true)->get();
        return view('contactos.clientes.create', compact('listas'));
    }

    public function store(Request $request) {
        $request->validate(['razon_social' => 'required', 'identificacion' => 'required|unique:clientes,identificacion']);
        Cliente::create($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
    }

    public function edit($id) {
        $cliente = Cliente::with('sucursales')->findOrFail($id);
        $listas = ListaPrecio::where('activa', true)->get(); // Cargar listas para el selector
        return view('contactos.clientes.edit', compact('cliente', 'listas'));
    }

    public function update(Request $request, $id) {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all()); // Ahora incluye lista_precio_id
        return redirect()->route('clientes.index')->with('success', 'Datos actualizados.');
    }

    public function destroy($id) {
        Cliente::destroy($id);
        return back()->with('success', 'Cliente eliminado.');
    }

    public function storeSucursal(Request $request, $cliente_id) {
        $request->validate(['nombre' => 'required', 'direccion' => 'required']);
        Sucursal::create(['cliente_id' => $cliente_id] + $request->all());
        return back()->with('success', 'Sucursal agregada.');
    }

    public function destroySucursal($id) {
        Sucursal::destroy($id);
        return back()->with('success', 'Sucursal eliminada.');
    }
}