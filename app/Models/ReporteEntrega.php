<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReporteEntrega extends Model {
    protected $table = 'reportes_entrega';
    protected $guarded = [];

    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function factura() { return $this->belongsTo(FacturaVenta::class, 'factura_id'); }
}