<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrdenEntregaDetalle extends Model {
    protected $table = 'orden_entrega_detalles';
    protected $guarded = [];

    public function item() { return $this->belongsTo(Item::class); }
}