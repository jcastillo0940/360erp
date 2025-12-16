<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrdenCompraDetalle extends Model {
    protected $table = 'orden_compra_detalles'; // Tabla correcta
    protected $guarded = [];

    public function ordenCompra() { return $this->belongsTo(OrdenCompra::class, 'orden_compra_id'); }
    public function item() { return $this->belongsTo(Item::class); }
}