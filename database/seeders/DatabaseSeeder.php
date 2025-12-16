<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Bodega;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // 1. Usuario Administrador (Necesario para entrar)
        if(User::where('email', 'admin@erp.com')->count() == 0) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@erp.com',
                'password' => Hash::make('password')
            ]);
        }

        // 2. Bodega Principal (Necesaria para que funcione el inventario)
        // Verificamos si la tabla existe antes de intentar crear para evitar errores en fresh
        try {
            if(Bodega::count() == 0){
                Bodega::create([
                    'nombre' => 'Bodega Principal', 
                    'ubicacion' => 'Matriz',
                    'es_principal' => true
                ]);
            }
        } catch (\Exception $e) {
            // Si la tabla no existe aun, se creará en la migración
        }
    }
}