<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $guarded = [];
    protected $table = 'items'; 

    // FIX: Usamos el evento 'creating' para asegurar que la columna 'precio_unitario' 
    // (que es NOT NULL) sea llenada con el valor de 'costo_unitario' durante la creación,
    // ya que el formulario no la envía.
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->precio_unitario)) {
                // Asumimos que precio_unitario (antiguo) es igual a costo_unitario (nuevo)
                $item->precio_unitario = $item->costo_unitario; 
            }
        });
    }

    public function preciosPorLista() {
        return $this->hasMany(ListaPrecioItem::class, 'item_id');
    }
}