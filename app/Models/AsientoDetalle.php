<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AsientoDetalle extends Model {
    protected $table = 'asientos_detalles';
    protected $guarded = [];
    public function cuenta() { return $this->belongsTo(CuentaContable::class, 'cuenta_id'); }
}