<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // COTIZACIONES
        if (!Schema::hasTable('cotizaciones')) {
            Schema::create('cotizaciones', function (Blueprint $table) {
                $table->id();
                $table->string('numero_cotizacion')->unique();
                $table->foreignId('cliente_id')->constrained('clientes');
                $table->date('fecha_emision');
                $table->date('fecha_vencimiento');
                $table->decimal('subtotal', 15, 2);
                $table->decimal('itbms', 15, 2)->default(0);
                $table->decimal('total', 15, 2);
                $table->text('terminos')->nullable();
                $table->enum('estado', ['borrador', 'enviada', 'aceptada', 'rechazada', 'convertida'])->default('borrador');
                $table->timestamps();
            });
        }

        // DETALLES COTIZACION
        if (!Schema::hasTable('cotizacion_detalles')) {
            Schema::create('cotizacion_detalles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
                $table->foreignId('item_id')->constrained('items');
                $table->decimal('cantidad', 10, 2);
                $table->decimal('precio', 10, 2);
                $table->decimal('total', 15, 2);
                $table->timestamps();
            });
        }

        // REPORTES DE ENTREGA
        if (!Schema::hasTable('reportes_entrega')) {
            Schema::create('reportes_entrega', function (Blueprint $table) {
                $table->id();
                $table->string('numero_entrega')->unique();
                $table->foreignId('factura_venta_id')->constrained('facturas_venta');
                $table->date('fecha_despacho');
                $table->string('direccion_entrega');
                $table->string('transportista')->nullable();
                $table->enum('estado', ['pendiente', 'entregado', 'devuelto'])->default('pendiente');
                $table->timestamps();
            });
        }

        // NOTAS DE CRÉDITO
        if (!Schema::hasTable('notas_credito')) {
            Schema::create('notas_credito', function (Blueprint $table) {
                $table->id();
                $table->string('numero_nota')->unique();
                $table->foreignId('factura_venta_id')->constrained('facturas_venta');
                $table->date('fecha');
                $table->decimal('monto', 15, 2);
                $table->text('motivo');
                $table->timestamps();
            });
        }
        
        // FACTURAS RECURRENTES
        if (!Schema::hasTable('facturas_recurrentes_config')) {
             Schema::create('facturas_recurrentes_config', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cliente_id')->constrained('clientes');
                $table->string('frecuencia');
                $table->date('proxima_ejecucion');
                $table->decimal('monto_base', 15, 2);
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('facturas_recurrentes_config');
        Schema::dropIfExists('notas_credito');
        Schema::dropIfExists('reportes_entrega');
        Schema::dropIfExists('cotizacion_detalles');
        Schema::dropIfExists('cotizaciones');
    }
};