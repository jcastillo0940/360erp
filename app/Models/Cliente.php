<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    protected $guarded = [];
    public function sucursales() { return $this->hasMany(Sucursal::class); }
    // Conexión a la Lista
    public function listaPrecio() { return $this->belongsTo(ListaPrecio::class, 'lista_precio_id'); }
}