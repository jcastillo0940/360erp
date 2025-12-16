<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('facturas_compra', function (Blueprint $table) {
            
            // 1. Asegurar que exista 'fecha_vencimiento' antes de usarla
            if (!Schema::hasColumn('facturas_compra', 'fecha_vencimiento')) {
                // La agregamos después de fecha_emision si existe, si no, al final
                if (Schema::hasColumn('facturas_compra', 'fecha_emision')) {
                    $table->date('fecha_vencimiento')->nullable()->after('fecha_emision');
                } else {
                    $table->date('fecha_vencimiento')->nullable();
                }
            }

            // 2. Agregar Condición de Pago (Contado, Crédito, etc)
            if (!Schema::hasColumn('facturas_compra', 'condicion_pago')) {
                $table->enum('condicion_pago', ['contado', 'credito_15', 'credito_30', 'credito_45', 'credito_60'])
                      ->default('contado')
                      ->after('fecha_vencimiento');
            }

            // 3. Agregar Estado de Pago
            if (!Schema::hasColumn('facturas_compra', 'estado_pago')) {
                $table->enum('estado_pago', ['pendiente', 'pagado', 'vencido'])
                      ->default('pendiente')
                      ->after('total');
            }
        });
    }

    public function down(): void {
        // No borramos columnas para proteger datos
    }
};