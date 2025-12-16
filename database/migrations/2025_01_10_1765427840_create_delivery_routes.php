<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        
        // 1. REPARTIDORES (USUARIOS)
        if (!Schema::hasTable('repartidores')) {
            Schema::create('repartidores', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('telefono')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }
        
        // 2. RUTAS DE REPARTO (Cabecera)
        if (!Schema::hasTable('rutas_reparto')) {
            Schema::create('rutas_reparto', function (Blueprint $table) {
                $table->id();
                $table->string('nombre'); // Ej: Ruta Los Pueblos Tarde
                $table->foreignId('repartidor_id')->constrained('repartidores')->onDelete('cascade');
                $table->string('vehiculo'); // Ej: Nissan Sentra
                $table->string('placa');
                $table->string('dias_activos'); // Ej: L,M,X,J,V,S
                $table->time('hora_inicio');
                $table->timestamps();
            });
        }
        
        // 3. ACTUALIZAR ORDENES DE ENTREGA (Transportista pasa a ser ruta_id)
        Schema::table('ordenes_entrega', function (Blueprint $table) {
            // Limpieza del campo antiguo
            if (Schema::hasColumn('ordenes_entrega', 'transportista')) {
                $table->dropColumn('transportista');
            }
            if (Schema::hasColumn('ordenes_entrega', 'placa')) {
                $table->dropColumn('placa');
            }
            
            // Nuevo campo Foreign Key
            if (!Schema::hasColumn('ordenes_entrega', 'ruta_reparto_id')) {
                $table->foreignId('ruta_reparto_id')->nullable()->after('sucursal_id')->constrained('rutas_reparto')->onDelete('set null');
            }
            
            // FIX SQL: Asegurar que fecha_entrega acepte NULL (si la BD lo creó como NOT NULL, debemos cambiarlo)
            $table->date('fecha_entrega')->nullable()->change();

        });
    }

    public function down(): void {
        Schema::dropIfExists('rutas_reparto');
        Schema::dropIfExists('repartidores');
    }
};