<?php
namespace App\Http\Controllers;
use App\Models\OrdenEntrega;
use App\Models\RutaReparto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RepartidorDashboardController extends Controller {
    // ESTE ES EL DASHBOARD QUE VE EL REPARTIDOR (Simulación)
    public function dashboard(Request $request) {
        // En un sistema real, usaríamos Auth::user()->repartidor_id
        // Aquí simulamos que es el repartidor 1
        $repartidorId = 1; 

        // 1. Encontrar rutas activas para HOY
        $dia_hoy = Carbon::now()->locale('es')->dayOfWeek; // 0=Dom, 1=Lun, etc.
        $dias_map = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 0 => 'D'];
        $codigo_dia = $dias_map[$dia_hoy];
        
        $rutas_activas = RutaReparto::where('repartidor_id', $repartidorId)
                                    ->where('dias_activos', 'LIKE', "%$codigo_dia%")
                                    ->get();

        // 2. Órdenes ASIGNADAS a esas rutas
        $ordenes_asignadas = OrdenEntrega::whereIn('ruta_reparto_id', $rutas_activas->pluck('id'))
                                        ->where('estado', 'pendiente')
                                        ->with(['cliente', 'sucursal'])
                                        ->orderBy('fecha_entrega')
                                        ->get();

        return view('reparto.dashboard', compact('rutas_activas', 'ordenes_asignadas'));
    }
    
    // Método para simular Check-in/Out (NO SE USA AÚN EN VISTAS)
    public function checkEntrega($orden_id) {
        // Lógica para marcar como entregado y actualizar stock si no se hizo al facturar
        OrdenEntrega::where('id', $orden_id)->update(['estado' => 'entregado']);
        return back()->with('success', 'Entrega finalizada.');
    }
}