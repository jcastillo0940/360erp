<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('facturas_compra', function (Blueprint $table) {
            
            // Agregar fecha_emision si no existe
            if (!Schema::hasColumn('facturas_compra', 'fecha_emision')) {
                $table->date('fecha_emision')
                      ->nullable()
                      ->after('orden_compra_id'); // La colocamos después del ID de la orden
            }
        });
    }

    public function down(): void {
        Schema::table('facturas_compra', function (Blueprint $table) {
            $table->dropColumn('fecha_emision');
        });
    }
};