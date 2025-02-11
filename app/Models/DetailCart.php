<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCart extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'detail_cart';
     protected $guarded = [];
    public $timestamps = true;

    public function cart() {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

}
