<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Bodega extends Model {
    protected $guarded = [];
    public function items() { return $this->belongsToMany(Item::class)->withPivot('cantidad'); }
}