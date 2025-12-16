<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('proveedores', function (Blueprint $table) {
            
            // 1. Agregar DV (Dígito Verificador) si falta
            if (!Schema::hasColumn('proveedores', 'dv')) {
                $table->string('dv')->nullable()->after('ruc');
            }

            // 2. Asegurar que exista Dirección
            if (!Schema::hasColumn('proveedores', 'direccion')) {
                $table->text('direccion')->nullable()->after('telefono');
            }
            
            // 3. Asegurar que exista Email
            if (!Schema::hasColumn('proveedores', 'email')) {
                $table->string('email')->nullable()->after('telefono');
            }
        });
    }

    public function down(): void {
        Schema::table('proveedores', function (Blueprint $table) {
            // No eliminamos columnas para proteger datos
        });
    }
};