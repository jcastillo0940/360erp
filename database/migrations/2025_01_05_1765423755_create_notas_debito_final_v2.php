<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Verificar si la tabla existe
        if (!Schema::hasTable('notas_debito_compra')) {
            // CREAR TABLA DESDE CERO
            Schema::create('notas_debito_compra', function (Blueprint $table) {
                $table->id();
                $table->string('numero_nota')->unique();
                $table->enum('tipo_nota', ['debito', 'credito'])->default('debito');
                $table->foreignId('factura_compra_id')->constrained('facturas_compra')->onDelete('cascade');
                $table->foreignId('proveedor_id')->constrained('proveedores');
                $table->date('fecha_emision');
                $table->string('motivo');
                $table->decimal('monto', 15, 2);
                $table->decimal('itbms', 15, 2)->default(0);
                $table->decimal('total', 15, 2);
                $table->text('observaciones')->nullable();
                $table->timestamps();
            });
        } else {
            // SI LA TABLA YA EXISTE, SOLO AGREGAR COLUMNAS FALTANTES
            Schema::table('notas_debito_compra', function (Blueprint $table) {
                if (!Schema::hasColumn('notas_debito_compra', 'tipo_nota')) {
                    $table->enum('tipo_nota', ['debito', 'credito'])->default('debito')->after('numero_nota');
                }
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('notas_debito_compra');
    }
};