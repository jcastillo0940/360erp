<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('items', function (Blueprint $table) {
            // Cambiamos la columna existente para aceptar valores nulos
            $table->text('descripcion')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('items', function (Blueprint $table) {
            // Revertir (Opcional)
            $table->text('descripcion')->nullable(false)->change();
        });
    }
};