<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('clientes', function (Blueprint $table) {
            
            // 1. Agregar Teléfono si falta
            if (!Schema::hasColumn('clientes', 'telefono')) {
                $table->string('telefono')->nullable()->after('email');
            }

            // 2. Agregar Dirección si falta
            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->text('direccion')->nullable()->after('telefono');
            }

            // 3. Agregar Condición de Pago si falta
            if (!Schema::hasColumn('clientes', 'condicion_pago')) {
                $table->string('condicion_pago')->default('contado')->after('direccion');
            }
            
            // 4. Asegurar campos opcionales
            if (!Schema::hasColumn('clientes', 'nombre_comercial')) {
                $table->string('nombre_comercial')->nullable();
            }
        });
    }

    public function down(): void {
        Schema::table('clientes', function (Blueprint $table) {
            // No eliminamos columnas en rollback para proteger datos en producción
        });
    }
};