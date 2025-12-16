<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FacturaVenta;
use App\Models\Item;
use App\Models\Cliente;
use App\Models\AsientoContable;
use DB;

class ReporteController extends Controller {
    
    public function index() {
        return view('reportes.dashboard');
    }

    // Ventas: Por Ítem
    public function ventasPorItem() {
        $ventas = DB::table('factura_detalles')
            ->join('items', 'factura_detalles.item_id', '=', 'items.id')
            ->select('items.nombre', DB::raw('SUM(factura_detalles.cantidad) as total_qty'), DB::raw('SUM(factura_detalles.total_linea) as total_money'))
            ->groupBy('items.nombre')
            ->get();
        return view('reportes.ventas_item', compact('ventas'));
    }

    // Financiero: Flujo de Caja (Simplificado)
    public function flujoCaja() {
        // Entradas (Pagos recibidos) vs Salidas (Pagos proveedores)
        return view('reportes.flujo_caja');
    }

    // Contable: Balance de Comprobación
    public function balanceComprobacion() {
        // Suma de débitos y créditos por cuenta
        return view('reportes.balance');
    }
}