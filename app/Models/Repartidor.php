<?php 
namespace App\Models; 
use Illuminate\Database\Eloquent\Model; 

class Repartidor extends Model { 
    protected $table = 'repartidores'; // FIX: Especificar el nombre en español
    protected $guarded=[]; 
    public function rutas(){return $this->hasMany(RutaReparto::class);} 
}