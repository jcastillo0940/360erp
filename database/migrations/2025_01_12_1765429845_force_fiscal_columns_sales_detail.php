<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('factura_venta_detalles', function (Blueprint $table) {
            // FIX: Tasa ITBMS aplicada (el error actual)
            if (!Schema::hasColumn('factura_venta_detalles', 'tasa_itbms_aplicada')) {
                $table->decimal('tasa_itbms_aplicada', 5, 2)->default(0.00)->after('total');
            }
            
            // FIX: Monto ITBMS calculado
            if (!Schema::hasColumn('factura_venta_detalles', 'itbms_monto')) {
                $table->decimal('itbms_monto', 15, 2)->default(0.00)->after('tasa_itbms_aplicada');
            }
        });
    }

    public function down(): void {
        Schema::table('factura_venta_detalles', function (Blueprint $table) {
            if (Schema::hasColumn('factura_venta_detalles', 'tasa_itbms_aplicada')) {
                $table->dropColumn('tasa_itbms_aplicada');
            }
            if (Schema::hasColumn('factura_venta_detalles', 'itbms_monto')) {
                $table->dropColumn('itbms_monto');
            }
        });
    }
};