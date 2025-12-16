<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Tabla de Movimientos (Kardex Real)
        if (!Schema::hasTable('movimientos_inventario')) {
            Schema::create('movimientos_inventario', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained('items');
                $table->foreignId('bodega_id')->nullable(); // Opcional por ahora
                $table->string('tipo'); // entrada, salida, ajuste_entrada, ajuste_salida
                $table->decimal('cantidad', 15, 2);
                $table->decimal('stock_anterior', 15, 2)->default(0);
                $table->decimal('stock_nuevo', 15, 2)->default(0);
                $table->string('referencia')->nullable(); // Ej: AJ-001, FAC-001
                $table->text('nota')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tabla de Ajustes (Cabecera)
        if (!Schema::hasTable('ajustes_inventario')) {
            Schema::create('ajustes_inventario', function (Blueprint $table) {
                $table->id();
                $table->string('codigo')->unique(); // AJ-timestamp
                $table->date('fecha');
                $table->string('tipo'); // entrada, salida
                $table->text('motivo')->nullable();
                $table->timestamps();
            });
        }

        // 3. Detalles del Ajuste
        if (!Schema::hasTable('ajuste_detalles')) {
            Schema::create('ajuste_detalles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ajuste_id')->constrained('ajustes_inventario')->onDelete('cascade');
                $table->foreignId('item_id')->constrained('items');
                $table->decimal('cantidad', 15, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('ajuste_detalles');
        Schema::dropIfExists('ajustes_inventario');
        Schema::dropIfExists('movimientos_inventario');
    }
};