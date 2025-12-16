<?php
namespace App\Http\Controllers;
use App\Models\Cotizacion;
use App\Models\FacturaVenta;
use App\Models\FacturaDetalle;
use App\Models\PagoRecibido;
use App\Models\PagoFactura;
use App\Models\AsientoContable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class FlujoComercialController extends Controller {

    // Convertir Cotización a Factura
    public function convertirCotizacion($id) {
        DB::transaction(function() use ($id) {
            $cotizacion = Cotizacion::with('detalles')->findOrFail($id);
            
            // Lógica de fechas de vencimiento
            $fechaEmision = Carbon::now();
            $fechaVencimiento = $fechaEmision->copy(); // Default contado
            
            // Crear Factura
            $factura = FacturaVenta::create([
                'cliente_id' => $cotizacion->cliente_id,
                'fecha_emision' => $fechaEmision,
                'fecha_vencimiento_pago' => $fechaVencimiento,
                'subtotal' => $cotizacion->subtotal ?? 0,
                'itbms' => $cotizacion->itbms ?? 0,
                'total' => $cotizacion->total,
                'saldo_pendiente' => $cotizacion->total,
                'numero_factura' => 'FAC-' . time() // Generador simple
            ]);

            // Copiar items...
            // Actualizar estado cotización
            $cotizacion->update(['estado' => 'convertida']);
        });
        return redirect()->back()->with('success', 'Cotización convertida');
    }

    // Registrar Pago y Conciliar
    public function storePago(Request $request) {
        DB::transaction(function() use ($request) {
            // 1. Crear Pago
            $pago = PagoRecibido::create([
                'cliente_id' => $request->cliente_id,
                'cuenta_banco_id' => $request->banco_id,
                'fecha_pago' => $request->fecha,
                'monto_total' => $request->monto,
                'referencia_bancaria' => $request->referencia
            ]);

            // 2. Distribuir monto entre facturas seleccionadas
            foreach($request->facturas as $facData) {
                if($facData['monto_a_pagar'] > 0) {
                    PagoFactura::create([
                        'pago_id' => $pago->id,
                        'factura_id' => $facData['id'],
                        'monto_aplicado' => $facData['monto_a_pagar']
                    ]);

                    // Reducir saldo factura
                    $factura = FacturaVenta::find($facData['id']);
                    $factura->saldo_pendiente -= $facData['monto_a_pagar'];
                    $factura->save();
                }
            }

            // 3. Generar Asiento Contable Automático (Bancos vs Cuentas por Cobrar)
            // Lógica simplificada de asiento...
        });
    }
}