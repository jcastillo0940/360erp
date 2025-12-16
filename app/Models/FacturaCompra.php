<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FacturaCompra extends Model {
    protected $table = 'facturas_compra';
    protected $guarded = [];

    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function ordenCompra() { return $this->belongsTo(OrdenCompra::class); }
    
    // Relación nueva: Una factura puede tener varias notas de débito (cargos extra)
    public function notasDebito() { return $this->hasMany(NotaDebitoCompra::class, 'factura_compra_id'); }
}