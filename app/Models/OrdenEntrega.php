<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrdenEntrega extends Model {
    protected $table = 'ordenes_entrega';
    protected $guarded = [];

    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function sucursal() { return $this->belongsTo(Sucursal::class); }
    public function detalles() { return $this->hasMany(OrdenEntregaDetalle::class, 'orden_entrega_id'); }
    
    // FIX: Relación con la Ruta de Reparto
    public function ruta() {
        return $this->belongsTo(RutaReparto::class, 'ruta_reparto_id');
    }
}