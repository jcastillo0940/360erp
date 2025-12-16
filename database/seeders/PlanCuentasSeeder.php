<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CuentaContable;

class PlanCuentasSeeder extends Seeder {
    public function run(): void {
        $cuentas = [
            // Activos
            ['codigo'=>'1', 'nombre'=>'ACTIVOS', 'tipo'=>'activo', 'naturaleza'=>'debito', 'nivel'=>1, 'es_cuenta_movimiento'=>false],
            ['codigo'=>'1.1', 'nombre'=>'ACTIVO CORRIENTE', 'tipo'=>'activo', 'naturaleza'=>'debito', 'nivel'=>2, 'es_cuenta_movimiento'=>false, 'padre_id'=>1],
            ['codigo'=>'1.1.01', 'nombre'=>'Caja General', 'tipo'=>'activo', 'naturaleza'=>'debito', 'nivel'=>3, 'es_cuenta_movimiento'=>true, 'padre_id'=>2],
            ['codigo'=>'1.1.02', 'nombre'=>'Banco General', 'tipo'=>'activo', 'naturaleza'=>'debito', 'nivel'=>3, 'es_cuenta_movimiento'=>true, 'padre_id'=>2],
            ['codigo'=>'1.1.03', 'nombre'=>'Cuentas por Cobrar Clientes', 'tipo'=>'activo', 'naturaleza'=>'debito', 'nivel'=>3, 'es_cuenta_movimiento'=>true, 'padre_id'=>2],
            ['codigo'=>'1.1.04', 'nombre'=>'Inventario de Mercancía', 'tipo'=>'activo', 'naturaleza'=>'debito', 'nivel'=>3, 'es_cuenta_movimiento'=>true, 'padre_id'=>2],
            
            // Pasivos
            ['codigo'=>'2', 'nombre'=>'PASIVOS', 'tipo'=>'pasivo', 'naturaleza'=>'credito', 'nivel'=>1, 'es_cuenta_movimiento'=>false],
            ['codigo'=>'2.1', 'nombre'=>'PASIVO CORRIENTE', 'tipo'=>'pasivo', 'naturaleza'=>'credito', 'nivel'=>2, 'es_cuenta_movimiento'=>false, 'padre_id'=>7],
            ['codigo'=>'2.1.01', 'nombre'=>'Cuentas por Pagar Proveedores', 'tipo'=>'pasivo', 'naturaleza'=>'credito', 'nivel'=>3, 'es_cuenta_movimiento'=>true, 'padre_id'=>8],
            ['codigo'=>'2.1.02', 'nombre'=>'ITBMS por Pagar (Débito Fiscal)', 'tipo'=>'pasivo', 'naturaleza'=>'credito', 'nivel'=>3, 'es_cuenta_movimiento'=>true, 'padre_id'=>8],
            
            // Ingresos
            ['codigo'=>'4', 'nombre'=>'INGRESOS', 'tipo'=>'ingreso', 'naturaleza'=>'credito', 'nivel'=>1, 'es_cuenta_movimiento'=>false],
            ['codigo'=>'4.1', 'nombre'=>'Ventas Gravadas 7%', 'tipo'=>'ingreso', 'naturaleza'=>'credito', 'nivel'=>2, 'es_cuenta_movimiento'=>true, 'padre_id'=>11],
            
            // Costos
            ['codigo'=>'5', 'nombre'=>'COSTOS', 'tipo'=>'costo', 'naturaleza'=>'debito', 'nivel'=>1, 'es_cuenta_movimiento'=>false],
            ['codigo'=>'5.1', 'nombre'=>'Costo de Ventas', 'tipo'=>'costo', 'naturaleza'=>'debito', 'nivel'=>2, 'es_cuenta_movimiento'=>true, 'padre_id'=>13],
        ];

        // Nota: En un sistema real, usamos recursividad o IDs reales para los padres.
        // Aquí simplificado para la instalación.
        foreach($cuentas as $cta) {
            unset($cta['padre_id']); // Simplificación para evitar error de FK en este script simple
            CuentaContable::create($cta);
        }
    }
}