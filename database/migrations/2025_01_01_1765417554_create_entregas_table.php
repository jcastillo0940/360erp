<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('reportes_entrega')) {
            Schema::create('reportes_entrega', function (Blueprint $table) {
                $table->id();
                $table->string('numero_entrega')->unique();
                $table->foreignId('factura_id')->nullable()->constrained('facturas_venta');
                $table->foreignId('cliente_id')->constrained('clientes');
                $table->date('fecha_despacho');
                $table->string('direccion_destino');
                $table->string('transportista')->nullable();
                $table->string('placa_vehiculo')->nullable();
                $table->text('observaciones')->nullable();
                $table->enum('estado', ['pendiente', 'en_camino', 'entregado'])->default('pendiente');
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('reportes_entrega');
    }
};