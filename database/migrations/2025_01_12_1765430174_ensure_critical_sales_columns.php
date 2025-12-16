<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'precio_venta')) {
                $table->decimal('precio_venta', 15, 2)->default(0.00);
            }
        });

        Schema::table('facturas_venta', function (Blueprint $table) {
            // Aseguramos que la columna 'estado' exista (fue removida en el rollback)
            if (!Schema::hasColumn('facturas_venta', 'estado')) {
                $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente')->after('total');
            }
        });
    }
    public function down(): void {}
};