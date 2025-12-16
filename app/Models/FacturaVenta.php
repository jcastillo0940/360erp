<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FacturaVenta extends Model {
    protected $table = 'facturas_venta';
    protected $guarded = [];

    public function cliente() { 
        return $this->belongsTo(Cliente::class); 
    }
    
    public function detalles() { 
        return $this->hasMany(FacturaVentaDetalle::class, 'factura_venta_id'); 
    }
    
    // FIX: Relación con la Sucursal
    public function sucursal() {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}