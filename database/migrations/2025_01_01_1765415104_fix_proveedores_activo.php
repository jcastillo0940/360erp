<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('proveedores', function (Blueprint $table) {
            // Verificar si falta la columna 'activo'
            if (!Schema::hasColumn('proveedores', 'activo')) {
                // Agregamos columna boolean (1 = activo, 0 = inactivo)
                $table->boolean('activo')->default(true)->after('direccion');
            }
        });
    }

    public function down(): void {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};