<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // PROVEEDORES
        if (!Schema::hasTable('proveedores')) {
            Schema::create('proveedores', function (Blueprint $table) {
                $table->id();
                $table->string('ruc')->unique();
                $table->string('dv')->nullable();
                $table->string('razon_social');
                $table->string('telefono')->nullable();
                $table->string('email')->nullable();
                $table->text('direccion')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }

        // ÓRDENES DE COMPRA
        if (!Schema::hasTable('ordenes_compra')) {
            Schema::create('ordenes_compra', function (Blueprint $table) {
                $table->id();
                $table->string('numero_orden')->unique();
                $table->foreignId('proveedor_id')->constrained('proveedores');
                $table->date('fecha_emision');
                $table->decimal('total', 15, 2);
                $table->enum('estado', ['pendiente', 'aprobada', 'recibida'])->default('pendiente');
                $table->timestamps();
            });
        }

        // FACTURAS COMPRA
        if (!Schema::hasTable('facturas_compra')) {
            Schema::create('facturas_compra', function (Blueprint $table) {
                $table->id();
                $table->string('numero_factura_proveedor');
                $table->foreignId('proveedor_id')->constrained('proveedores');
                $table->date('fecha_emision');
                $table->decimal('total', 15, 2);
                $table->foreignId('orden_compra_id')->nullable()->constrained('ordenes_compra');
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('facturas_compra');
        Schema::dropIfExists('ordenes_compra');
        Schema::dropIfExists('proveedores');
    }
};