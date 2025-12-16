<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Cotizaciones
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('numero_cotizacion');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->decimal('total', 15, 2);
            $table->enum('estado', ['borrador', 'enviada', 'aceptada', 'rechazada', 'convertida'])->default('borrador');
            $table->timestamps();
        });

        // Reportes de Entrega (Conduce)
        Schema::create('reportes_entrega', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('factura_id')->nullable()->constrained('facturas_venta'); // Puede estar ligado o no
            $table->date('fecha_entrega');
            $table->string('direccion_entrega');
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Actualizar Facturas con campos de crédito
        Schema::table('facturas_venta', function (Blueprint $table) {
            $table->enum('condicion_pago', ['contado', 'credito_30', 'credito_45', 'credito_60'])->default('contado');
            $table->date('fecha_vencimiento_pago')->nullable();
            $table->foreignId('vendedor_id')->nullable()->constrained('users'); // Asumiendo tabla users
            $table->boolean('es_recurrente')->default(false);
            $table->integer('frecuencia_dias')->nullable(); // Para recurrentes
            $table->decimal('saldo_pendiente', 15, 2)->default(0); // Para control de pagos
        });

        // Pagos Recibidos (Cabecera)
        Schema::create('pagos_recibidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('cuenta_banco_id')->constrained('bancos'); // A dónde entró el dinero
            $table->date('fecha_pago');
            $table->string('referencia_bancaria')->nullable();
            $table->decimal('monto_total', 15, 2);
            $table->decimal('retenciones', 15, 2)->default(0); // ITBMS retenido por cliente
            $table->text('comentarios')->nullable();
            $table->timestamps();
        });

        // Pagos Detalle (Aplicación a facturas)
        Schema::create('pago_factura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained('pagos_recibidos');
            $table->foreignId('factura_id')->constrained('facturas_venta');
            $table->decimal('monto_aplicado', 15, 2);
            $table->timestamps();
        });

        // Notas de Crédito
        Schema::create('notas_credito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_origen_id')->constrained('facturas_venta');
            $table->string('motivo');
            $table->decimal('total', 15, 2);
            $table->boolean('aplicada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pago_factura');
        Schema::dropIfExists('pagos_recibidos');
        Schema::dropIfExists('cotizaciones');
    }
};