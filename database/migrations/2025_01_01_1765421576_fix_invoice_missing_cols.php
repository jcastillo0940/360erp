<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('facturas_compra', function (Blueprint $table) {
            
            // 1. Número de Factura del Proveedor (Vital)
            if (!Schema::hasColumn('facturas_compra', 'numero_factura_proveedor')) {
                $table->string('numero_factura_proveedor')->after('id');
            }

            // 2. Desglose de Montos (Para contabilidad)
            if (!Schema::hasColumn('facturas_compra', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->default(0)->after('condicion_pago');
            }
            if (!Schema::hasColumn('facturas_compra', 'itbms')) {
                $table->decimal('itbms', 15, 2)->default(0)->after('subtotal');
            }
            
            // 3. Saldo Pendiente (Para abonos futuros)
            if (!Schema::hasColumn('facturas_compra', 'saldo_pendiente')) {
                $table->decimal('saldo_pendiente', 15, 2)->default(0)->after('total');
            }
        });
    }

    public function down(): void {
        // No eliminamos para proteger datos
    }
};