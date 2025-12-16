<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('factura_venta_detalles', function (Blueprint $table) {
            $table->decimal('tasa_itbms', 5, 2)->default(0)->after('precio_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factura_venta_detalles', function (Blueprint $table) {
            $table->dropColumn('tasa_itbms');
        });
    }
};
