<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PagoProveedor extends Model {
    protected $table = 'pagos_proveedor';
    protected $guarded = [];

    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function detalles() { return $this->hasMany(PagoProveedorDetalle::class, 'pago_proveedor_id'); }
}