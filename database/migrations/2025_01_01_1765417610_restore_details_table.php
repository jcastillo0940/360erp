<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('orden_compra_detalles')) {
            Schema::create('orden_compra_detalles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('orden_compra_id')->constrained('ordenes_compra')->onDelete('cascade');
                $table->foreignId('item_id')->nullable();
                $table->string('descripcion')->nullable();
                $table->decimal('cantidad', 15, 2);
                $table->decimal('costo_unitario', 15, 2);
                $table->decimal('total', 15, 2);
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('orden_compra_detalles');
    }
};