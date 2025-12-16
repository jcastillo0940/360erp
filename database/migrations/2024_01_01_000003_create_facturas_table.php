<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('facturas_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('numero_factura')->unique();
            $table->date('fecha_emision');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('itbms', 15, 2);
            $table->decimal('total', 15, 2);
            // Campos Facturación Electrónica DGI
            $table->string('cufe')->nullable();
            $table->string('qr_url')->nullable();
            $table->text('xml_firmado')->nullable();
            $table->enum('estado_dgi', ['pendiente', 'autorizado', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });

        Schema::create('factura_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas_venta')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio', 10, 2);
            $table->decimal('itbms', 10, 2);
            $table->decimal('total_linea', 15, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('factura_detalles');
        Schema::dropIfExists('facturas_venta');
    }
};