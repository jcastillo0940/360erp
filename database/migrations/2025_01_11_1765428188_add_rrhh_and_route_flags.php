<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. REPARTIDORES: Campos de RRHH
        Schema::table('repartidores', function (Blueprint $table) {
            if (!Schema::hasColumn('repartidores', 'tipo_pago')) {
                $table->enum('tipo_pago', ['hora', 'dia', 'quincena'])->default('quincena')->after('telefono');
                $table->decimal('tarifa', 10, 2)->default(0)->after('tipo_pago');
                $table->time('hora_entrada')->nullable()->after('tarifa');
            }
        });

        // 2. RUTAS: Campo de Logística
        Schema::table('rutas_reparto', function (Blueprint $table) {
            if (!Schema::hasColumn('rutas_reparto', 'requiere_carga')) {
                $table->boolean('requiere_carga')->default(false)->after('hora_inicio');
            }
        });
    }

    public function down(): void {}
};