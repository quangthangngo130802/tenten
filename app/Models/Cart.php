<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
     protected $guarded = [];
    public $timestamps = true;

    public function details()
    {
        return $this->hasMany(DetailCart::class, 'cart_id', 'id');
    }
}
