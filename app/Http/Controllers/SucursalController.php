<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Cliente;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sucursales = Sucursal::with('cliente')->latest()->paginate(10);
        return view('ventas.sucursales.index', compact('sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::where('activo', true)->get(); // Solo clientes activos
        return view('ventas.sucursales.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'contacto' => 'nullable|string|max:100',
        ]);

        Sucursal::create($request->all());

        return redirect()->route('sucursales.index')
            ->with('success', 'Sucursal creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sucursal $sucursal)
    {
        // En este ERP quizás no necesitemos vista de detalle individual por ahora
        return redirect()->route('sucursales.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sucursal $sucursal)
    {
        $clientes = Cliente::where('activo', true)->orWhere('id', $sucursal->cliente_id)->get();
        return view('ventas.sucursales.edit', compact('sucursal', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sucursal $sucursal)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'contacto' => 'nullable|string|max:100',
        ]);

        $sucursal->update($request->all());

        return redirect()->route('sucursales.index')
            ->with('success', 'Sucursal actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sucursal $sucursal)
    {
        try {
            $sucursal->delete();
            return redirect()->route('sucursales.index')->with('success', 'Sucursal eliminada.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar la sucursal por dependencias.');
        }
    }
}
