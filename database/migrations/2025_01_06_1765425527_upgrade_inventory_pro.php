<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        
        // 1. LOTES (Trazabilidad)
        if (!Schema::hasTable('lotes')) {
            Schema::create('lotes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
                $table->string('codigo_lote'); // El número impreso en la caja
                $table->date('fecha_vencimiento')->nullable();
                $table->decimal('cantidad', 15, 2)->default(0);
                $table->decimal('costo_lote', 15, 2)->default(0); // Costo específico de este lote
                $table->timestamps();
            });
        }

        // 2. LISTAS DE PRECIOS
        if (!Schema::hasTable('listas_precios')) {
            Schema::create('listas_precios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre'); // Ej: Mayorista
                $table->decimal('porcentaje_descuento', 5, 2)->default(0); // Opcional
                $table->boolean('activa')->default(true);
                $table->timestamps();
            });
        }

        // 3. PRECIOS POR LISTA (Detalle)
        if (!Schema::hasTable('lista_precio_items')) {
            Schema::create('lista_precio_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lista_precio_id')->constrained('listas_precios')->onDelete('cascade');
                $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
                $table->decimal('precio', 15, 2);
                $table->timestamps();
            });
        }

        // 4. MERMAS (Pérdidas)
        if (!Schema::hasTable('mermas')) {
            Schema::create('mermas', function (Blueprint $table) {
                $table->id();
                $table->string('codigo')->unique();
                $table->date('fecha');
                $table->foreignId('item_id')->constrained('items');
                $table->foreignId('lote_id')->nullable()->constrained('lotes'); // Saber qué lote se dañó
                $table->decimal('cantidad', 15, 2);
                $table->string('motivo'); // Vencimiento, Daño, Robo
                $table->text('observaciones')->nullable();
                $table->decimal('costo_perdido', 15, 2); // Cuánto dinero perdimos
                $table->timestamps();
            });
        }

        // 5. ACTUALIZAR ITEMS (Atributos y Variantes)
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'es_variante')) {
                $table->boolean('es_variante')->default(false); // Si es sabor Fresa
                $table->foreignId('item_padre_id')->nullable()->constrained('items'); // Link al "Boli Genérico"
                $table->string('atributo_nombre')->nullable(); // Ej: Sabor
                $table->string('atributo_valor')->nullable(); // Ej: Fresa
            }
        });
    }

    public function down(): void {}
};