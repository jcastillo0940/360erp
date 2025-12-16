<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        
        // 1. SUCURSALES DE CLIENTES
        if (!Schema::hasTable('sucursales')) {
            Schema::create('sucursales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
                $table->string('nombre'); // Ej: Sucursal Los Pueblos
                $table->string('direccion');
                $table->string('telefono')->nullable();
                $table->string('contacto')->nullable();
                $table->timestamps();
            });
        }

        // 2. ORDENES DE ENTREGA (Estructura tipo Factura)
        // Eliminamos la tabla anterior si existe para evitar conflictos de estructura
        Schema::dropIfExists('pago_proveedor_detalles'); // Limpieza preventiva si hubiera
        Schema::dropIfExists('reportes_entrega'); // La versión vieja

        if (!Schema::hasTable('ordenes_entrega')) {
            Schema::create('ordenes_entrega', function (Blueprint $table) {
                $table->id();
                $table->string('numero_orden')->unique(); // OE-Timestamp
                $table->foreignId('cliente_id')->constrained('clientes');
                $table->foreignId('sucursal_id')->nullable()->constrained('sucursales'); // Dirección específica
                $table->date('fecha_emision');
                $table->date('fecha_entrega');
                $table->enum('estado', ['pendiente', 'entregado', 'facturado'])->default('pendiente');
                $table->text('observaciones')->nullable();
                $table->string('transportista')->nullable();
                $table->string('placa')->nullable();
                $table->decimal('total', 15, 2)->default(0); // Referencial
                $table->timestamps();
            });
        }

        // 3. DETALLES DE ORDEN DE ENTREGA
        if (!Schema::hasTable('orden_entrega_detalles')) {
            Schema::create('orden_entrega_detalles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('orden_entrega_id')->constrained('ordenes_entrega')->onDelete('cascade');
                $table->foreignId('item_id')->constrained('items');
                $table->string('descripcion');
                $table->decimal('cantidad', 15, 2);
                $table->decimal('precio_unitario', 15, 2); // Para saber cuánto se facturará
                $table->decimal('total', 15, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('orden_entrega_detalles');
        Schema::dropIfExists('ordenes_entrega');
        Schema::dropIfExists('sucursales');
    }
};