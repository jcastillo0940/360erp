<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FacturaVentaDetalle extends Model {
    protected $table = 'factura_venta_detalles'; // <--- LA TABLA QUE YA TIENES
    protected $guarded = [];

    public function factura() { return $this->belongsTo(FacturaVenta::class, 'factura_venta_id'); }
    public function item() { return $this->belongsTo(Item::class); }
}