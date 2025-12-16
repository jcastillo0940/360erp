<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bodegas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ubicacion')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
        });

        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('bodega_id')->constrained('bodegas');
            $table->enum('tipo', ['entrada', 'salida', 'ajuste', 'transferencia']);
            $table->decimal('cantidad', 15, 3);
            $table->decimal('costo_unitario', 15, 2)->default(0);
            $table->string('referencia')->nullable(); // Ej: Numero Factura Compra
            $table->timestamps();
        });
        
        // Tabla pivote para saldo por bodega
        Schema::create('bodega_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bodega_id')->constrained('bodegas');
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('cantidad', 15, 3)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('bodega_item');
        Schema::dropIfExists('movimientos_inventario');
        Schema::dropIfExists('bodegas');
    }
};