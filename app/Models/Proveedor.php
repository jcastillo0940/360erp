<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model {
    protected $table = 'proveedores';
    protected $guarded = [];

    // Relación para obtener las facturas pendientes de pago
    public function facturas() {
        return $this->hasMany(FacturaCompra::class, 'proveedor_id');
    }
}