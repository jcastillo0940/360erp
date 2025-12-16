<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model {
    protected $table = 'cotizaciones';
    protected $guarded = [];

    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function detalles() { return $this->hasMany(CotizacionDetalle::class); }
}