<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model {
    protected $table = 'cuentas_contables';
    protected $guarded = [];
    
    public function hijos() { return $this->hasMany(CuentaContable::class, 'padre_id'); }
    public function padre() { return $this->belongsTo(CuentaContable::class, 'padre_id'); }
}