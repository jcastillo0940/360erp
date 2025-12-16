<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('facturas_compra', function (Blueprint $table) {
            
            // Agregar la conexión con Orden de Compra si falta
            if (!Schema::hasColumn('facturas_compra', 'orden_compra_id')) {
                // foreignId crea la columna y la restricción automáticamente
                $table->foreignId('orden_compra_id')
                      ->nullable()
                      ->after('proveedor_id')
                      ->constrained('ordenes_compra')
                      ->onDelete('set null'); 
            }
        });
    }

    public function down(): void {
        // No eliminamos para proteger datos
    }
};