<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('facturas_venta', function (Blueprint $table) {
            // FIX: Agregar estado de pago (pendiente, pagada, anulada)
            if (!Schema::hasColumn('facturas_venta', 'estado')) {
                $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente')->after('total');
            }
        });
    }

    public function down(): void {
        Schema::table('facturas_venta', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};