<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('items', function (Blueprint $table) {
            $table->enum('tipo_item', ['producto', 'servicio', 'combo', 'variante'])->default('producto');
            $table->string('unidad_medida')->default('unidad');
            $table->string('referencia_fabrica')->nullable();
            
            // Configuración Contable por Ítem
            $table->foreignId('cuenta_ingreso_id')->nullable()->constrained('cuentas_contables');
            $table->foreignId('cuenta_inventario_id')->nullable()->constrained('cuentas_contables');
            $table->foreignId('cuenta_costo_id')->nullable()->constrained('cuentas_contables');
            
            // Variantes
            $table->json('atributos')->nullable(); // Ej: {"Talla": "M", "Color": "Rojo"}
            $table->foreignId('padre_variante_id')->nullable()->constrained('items')->onDelete('cascade');
        });

        // Listas de Precios
        Schema::create('listas_precios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Mayorista, Detal
            $table->enum('tipo', ['fijo', 'porcentaje']); // Valor fijo o % de descuento sobre base
            $table->decimal('valor', 10, 2)->default(0); // Si es %, es el descuento.
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        // Precios por Item
        Schema::create('item_lista_precio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('lista_precio_id')->constrained('listas_precios');
            $table->decimal('precio', 15, 2);
            $table->timestamps();
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('item_lista_precio');
        Schema::dropIfExists('listas_precios');
    }
};