<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaDebitoCompra extends Model {
    protected $table = 'notas_debito_compra';
    
    protected $guarded = [];

    // Relaciones
    public function factura() {
        return $this->belongsTo(FacturaCompra::class, 'factura_compra_id');
    }

    public function proveedor() {
        return $this->belongsTo(Proveedor::class);
    }
}