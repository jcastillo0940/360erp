<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Catálogo de Cuentas
        Schema::create('cuentas_contables', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Ej: 1.1.01
            $table->string('nombre');
            $table->enum('tipo', ['activo', 'pasivo', 'patrimonio', 'ingreso', 'costo', 'gasto']);
            $table->enum('naturaleza', ['debito', 'credito']);
            $table->boolean('es_cuenta_movimiento')->default(true); // Si false, es cuenta padre
            $table->integer('nivel')->default(1);
            $table->foreignId('padre_id')->nullable()->constrained('cuentas_contables');
            $table->timestamps();
        });

        // Libro Diario (Cabecera)
        Schema::create('asientos_contables', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('glosa'); // Descripción
            $table->string('referencia_documento')->nullable(); // Ej: FACT-001
            $table->enum('origen', ['manual', 'ventas', 'compras', 'bancos', 'cierre'])->default('manual');
            $table->decimal('total_debe', 20, 2);
            $table->decimal('total_haber', 20, 2);
            $table->boolean('cuadrado')->default(true);
            $table->timestamps();
        });

        // Libro Diario (Detalles)
        Schema::create('asientos_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asiento_id')->constrained('asientos_contables')->onDelete('cascade');
            $table->foreignId('cuenta_id')->constrained('cuentas_contables');
            $table->decimal('debe', 20, 2)->default(0);
            $table->decimal('haber', 20, 2)->default(0);
            $table->timestamps();
        });

        // Bancos
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('numero_cuenta');
            $table->string('tipo_cuenta'); // Corriente, Ahorros
            $table->foreignId('cuenta_contable_id')->constrained('cuentas_contables'); // Link a contabilidad
            $table->decimal('saldo_inicial', 20, 2)->default(0);
            $table->decimal('saldo_actual', 20, 2)->default(0);
            $table->timestamps();
        });
        
        // Conciliación Bancaria
        Schema::create('conciliaciones_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_id')->constrained('bancos');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('saldo_extracto', 20, 2);
            $table->decimal('saldo_libro', 20, 2);
            $table->enum('estado', ['borrador', 'conciliado'])->default('borrador');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('conciliaciones_bancarias');
        Schema::dropIfExists('bancos');
        Schema::dropIfExists('asientos_detalles');
        Schema::dropIfExists('asientos_contables');
        Schema::dropIfExists('cuentas_contables');
    }
};