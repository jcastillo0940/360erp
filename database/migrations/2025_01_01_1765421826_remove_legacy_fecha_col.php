<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('facturas_compra', function (Blueprint $table) {
            // Si existe la columna 'fecha' (la antigua), la eliminamos
            if (Schema::hasColumn('facturas_compra', 'fecha')) {
                $table->dropColumn('fecha');
            }
        });
    }

    public function down(): void {
        // No revertimos para evitar reintroducir el error
    }
};