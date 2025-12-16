<?php 
namespace App\Models; 
use Illuminate\Database\Eloquent\Model; 

class RutaReparto extends Model { 
    protected $table = 'rutas_reparto'; // Aseguramos el nombre en español
    protected $guarded=[]; 
    public function repartidor(){return $this->belongsTo(Repartidor::class);} 
    public function ordenes(){return $this->hasMany(OrdenEntrega::class);} 
}