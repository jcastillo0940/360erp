<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PagoProveedorDetalle extends Model {
    protected $table = 'pago_proveedor_detalles';
    protected $guarded = [];

    public function factura() { return $this->belongsTo(FacturaCompra::class, 'factura_compra_id'); }
}