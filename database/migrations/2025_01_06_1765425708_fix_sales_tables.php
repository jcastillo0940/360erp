<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        
        // 1. Cabecera de Factura de Venta
        if (!Schema::hasTable('facturas_venta')) {
            Schema::create('facturas_venta', function (Blueprint $table) {
                $table->id();
                $table->string('numero_factura')->unique();
                $table->foreignId('cliente_id')->constrained('clientes');
                $table->date('fecha_emision');
                $table->date('fecha_vencimiento');
                $table->enum('condicion_pago', ['contado', 'credito_15', 'credito_30'])->default('contado');
                $table->decimal('subtotal', 15, 2);
                $table->decimal('itbms', 15, 2);
                $table->decimal('total', 15, 2);
                $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
                $table->timestamps();
            });
        }

        // 2. Detalle de Factura (LA TABLA QUE FALTABA)
        if (!Schema::hasTable('factura_venta_detalles')) {
            Schema::create('factura_venta_detalles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('factura_venta_id')->constrained('facturas_venta')->onDelete('cascade');
                $table->foreignId('item_id')->constrained('items');
                $table->string('descripcion')->nullable();
                $table->decimal('cantidad', 15, 2);
                $table->decimal('precio_unitario', 15, 2); // Precio de venta
                $table->decimal('total', 15, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('factura_venta_detalles');
        Schema::dropIfExists('facturas_venta');
    }
};