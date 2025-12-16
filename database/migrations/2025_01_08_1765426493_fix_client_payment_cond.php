<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'condicion_pago')) {
                $table->string('condicion_pago')->default('contado')->after('email');
            }
        });
        
        // Asegurar tabla sucursales (por si acaso no corrió el script anterior)
        if (!Schema::hasTable('sucursales')) {
            Schema::create('sucursales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
                $table->string('nombre');
                $table->string('direccion');
                $table->string('telefono')->nullable();
                $table->string('contacto')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void {}
};