<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('factura_venta_detalles', function (Blueprint $table) {
            if (!Schema::hasColumn('factura_venta_detalles', 'tasa_itbms_aplicada')) {
                $table->decimal('tasa_itbms_aplicada', 5, 2)->default(0.00);
            }
            if (!Schema::hasColumn('factura_venta_detalles', 'itbms_monto')) {
                $table->decimal('itbms_monto', 15, 2)->default(0.00);
            }
        });
    }
    public function down(): void {}
};