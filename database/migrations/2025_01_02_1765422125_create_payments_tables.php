<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabla Cabecera de Pagos
        if (!Schema::hasTable('pagos_proveedor')) {
            Schema::create('pagos_proveedor', function (Blueprint $table) {
                $table->id();
                $table->string('numero_pago')->unique(); // Ej: PAG-001
                $table->foreignId('proveedor_id')->constrained('proveedores');
                $table->date('fecha_pago');
                $table->string('metodo_pago'); // Cheque, ACH, Efectivo
                $table->string('referencia')->nullable(); // N° Cheque o Transacción
                $table->decimal('monto_total', 15, 2);
                $table->text('observaciones')->nullable();
                $table->timestamps();
            });
        }

        // Tabla Detalle (Relación Pago -> Facturas)
        if (!Schema::hasTable('pago_proveedor_detalles')) {
            Schema::create('pago_proveedor_detalles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pago_proveedor_id')->constrained('pagos_proveedor')->onDelete('cascade');
                $table->foreignId('factura_compra_id')->constrained('facturas_compra');
                $table->decimal('monto_aplicado', 15, 2); // Cuánto se abonó a esta factura específica
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('pago_proveedor_detalles');
        Schema::dropIfExists('pagos_proveedor');
    }
};