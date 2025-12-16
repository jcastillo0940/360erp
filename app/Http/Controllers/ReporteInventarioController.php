<?php
namespace App\Http\Controllers;
use App\Models\FacturaVenta; // Asumiendo que tienes este modelo
use App\Models\Item;
use Illuminate\Http\Request;
use DB;

class ReporteInventarioController extends Controller {
    
    public function rentabilidad(Request $request) {
        // Calcular rentabilidad básica: (Precio Venta - Costo) * Cantidad Vendida
        // NOTA: Esto requiere que tengas un modelo FacturaVentaDetalle. 
        // Si no existe, usamos query directo a la base de datos por robustez.
        
        $ventas = DB::table('factura_venta_detalles')
            ->join('items', 'factura_venta_detalles.item_id', '=', 'items.id')
            ->select(
                'items.nombre',
                'items.codigo',
                DB::raw('SUM(factura_venta_detalles.cantidad) as cantidad_vendida'),
                DB::raw('SUM(factura_venta_detalles.total) as venta_total'),
                // Asumimos costo promedio actual para simplificar, idealmente se guarda costo histórico
                DB::raw('SUM(factura_venta_detalles.cantidad * items.costo_unitario) as costo_total')
            )
            ->groupBy('items.id', 'items.nombre', 'items.codigo')
            ->get();

        return view('inventario.reportes.rentabilidad', compact('ventas'));
    }
}