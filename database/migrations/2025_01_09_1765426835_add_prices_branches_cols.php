<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Asignar Lista de Precios al Cliente
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'lista_precio_id')) {
                $table->foreignId('lista_precio_id')->nullable()->after('condicion_pago')->constrained('listas_precios')->onDelete('set null');
            }
        });

        // 2. Asignar Sucursal a la Factura
        Schema::table('facturas_venta', function (Blueprint $table) {
            if (!Schema::hasColumn('facturas_venta', 'sucursal_id')) {
                $table->foreignId('sucursal_id')->nullable()->after('cliente_id')->constrained('sucursales')->onDelete('set null');
            }
        });
    }

    public function down(): void {}
};