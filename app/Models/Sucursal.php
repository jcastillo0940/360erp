<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model {
    // Definimos la tabla explícitamente para evitar que Laravel busque 'sucursals'
    protected $table = 'sucursales';
    
    protected $guarded = [];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}