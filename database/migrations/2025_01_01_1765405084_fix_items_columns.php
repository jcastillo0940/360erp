<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('items', function (Blueprint $table) {
            
            // 1. Agregar Nombre (Principal)
            if (!Schema::hasColumn('items', 'nombre')) {
                $table->string('nombre')->after('codigo')->nullable();
            }

            // 2. Agregar Descripción (Secundaria) si falta
            if (!Schema::hasColumn('items', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('nombre');
            }

            // 3. Agregar Tipo (Producto/Servicio)
            if (!Schema::hasColumn('items', 'tipo')) {
                $table->string('tipo')->default('producto')->after('id');
            }

            // 4. Agregar Costo Unitario
            if (!Schema::hasColumn('items', 'costo_unitario')) {
                $table->decimal('costo_unitario', 15, 2)->default(0)->after('precio_unitario');
            }

            // 5. Agregar Stock (Si acaso faltara por errores previos)
            if (!Schema::hasColumn('items', 'stock')) {
                $table->decimal('stock', 15, 2)->default(0);
            }
            
            // 6. Agregar Activo
            if (!Schema::hasColumn('items', 'activo')) {
                $table->boolean('activo')->default(true);
            }
        });
    }

    public function down(): void {
        // No eliminamos columnas para seguridad de datos
    }
};