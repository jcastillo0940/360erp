<?php
namespace App\Http\Controllers;
use App\Models\ReporteEntrega;
use App\Models\FacturaVenta;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ReporteEntregaController extends Controller {
    public function index() {
        $entregas = ReporteEntrega::with('cliente', 'factura')->latest()->paginate(10);
        return view('ventas.entregas.index', compact('entregas'));
    }
    public function create(Request $request) {
        $factura = null;
        if($request->has('factura_id')) $factura = FacturaVenta::with('cliente')->find($request->factura_id);
        $clientes = Cliente::where('activo', true)->get();
        if($clientes->isEmpty()) $clientes = Cliente::all();
        return view('ventas.entregas.create', compact('factura', 'clientes'));
    }
    public function store(Request $request) {
        ReporteEntrega::create([
            'numero_entrega' => 'ENT-' . time(),
            'factura_id' => $request->factura_id,
            'cliente_id' => $request->cliente_id,
            'fecha_despacho' => $request->fecha_despacho,
            'direccion_destino' => $request->direccion_destino,
            'transportista' => $request->transportista,
            'placa_vehiculo' => $request->placa_vehiculo,
            'observaciones' => $request->observaciones,
            'estado' => 'pendiente'
        ]);
        return redirect()->route('entregas.index')->with('success', 'Orden generada.');
    }
    
    // MÉTODO PDF ACTUALIZADO
    public function imprimir($id) {
        $entrega = ReporteEntrega::with(['cliente', 'factura.detalles.item'])->findOrFail($id);
        return view('ventas.entregas.pdf', compact('entrega')); // Ahora usa el diseño unificado
    }
}