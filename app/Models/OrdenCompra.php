<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model {
    protected $table = 'ordenes_compra'; // Tabla correcta
    protected $guarded = [];

    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function detalles() { return $this->hasMany(OrdenCompraDetalle::class, 'orden_compra_id'); }
    public function factura() { return $this->hasOne(FacturaCompra::class, 'orden_compra_id'); }
}