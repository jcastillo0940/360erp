<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('facturas_venta', function (Blueprint $table) {
            // FIX: Agregar fecha_vencimiento, que es requerida para el crédito
            if (!Schema::hasColumn('facturas_venta', 'fecha_vencimiento')) {
                $table->date('fecha_vencimiento')->nullable()->after('fecha_emision');
            }
        });
    }

    public function down(): void {
        Schema::table('facturas_venta', function (Blueprint $table) {
            $table->dropColumn('fecha_vencimiento');
        });
    }
};