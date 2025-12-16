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
    }
    public function down(): void {}
};