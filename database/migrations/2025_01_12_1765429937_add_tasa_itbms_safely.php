<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('items', function (Blueprint $table) {
            // FIX: Se agrega sin la cláusula 'after' para evitar el error de columna desconocida
            if (!Schema::hasColumn('items', 'tasa_itbms')) {
                $table->decimal('tasa_itbms', 5, 2)->default(7.00); 
            }
        });
    }
    public function down(): void {}
};