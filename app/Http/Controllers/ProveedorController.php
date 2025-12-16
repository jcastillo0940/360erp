<?php
namespace App\Http\Controllers;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller {
    public function index() {
        $proveedores = Proveedor::latest()->paginate(10);
        return view('contactos.proveedores.index', compact('proveedores'));
    }

    public function create() { return view('contactos.proveedores.form'); }

    public function store(Request $request) {
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|unique:proveedores',
            'email' => 'nullable|email'
        ]);
        Proveedor::create($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado correctamente.');
    }

    public function edit(Proveedor $proveedor) { return view('contactos.proveedores.form', compact('proveedor')); }

    public function update(Request $request, Proveedor $proveedor) {
        $proveedor->update($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor) {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}