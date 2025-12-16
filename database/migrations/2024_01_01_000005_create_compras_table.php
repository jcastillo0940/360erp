<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('ruc')->unique();
            $table->string('razon_social');
            $table->string('telefono')->nullable();
            $table->timestamps();
        });

        Schema::create('facturas_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->string('numero_factura');
            $table->date('fecha');
            $table->decimal('total', 15, 2);
            $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('facturas_compra');
        Schema::dropIfExists('proveedores');
    }
};