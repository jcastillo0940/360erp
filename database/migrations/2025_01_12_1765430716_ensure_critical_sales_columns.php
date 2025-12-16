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
            if (!Schema::hasColumn('facturas_venta', 'estado')) {
                $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente')->after('total');
            }
            if (!Schema::hasColumn('facturas_venta', 'fecha_vencimiento')) {
                $table->date('fecha_vencimiento')->nullable();
            }
        });
    }
    public function down(): void {}
};