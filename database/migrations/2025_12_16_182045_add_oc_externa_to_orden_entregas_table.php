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
        Schema::table('ordenes_entrega', function (Blueprint $table) {
            $table->string('oc_externa', 10)->nullable()->after('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordenes_entrega', function (Blueprint $table) {
            $table->dropColumn('oc_externa');
        });
    }
};
