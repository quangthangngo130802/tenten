<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;
    protected $table = 'transaction_histories';

     protected $guarded = [];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected static function booted()
    {
        static::creating(function ($order) {
            $order->code = self::generateOrderCode();
        });
    }

    private static function generateOrderCode()
    {
        do {
            $code = '#' . rand(100, 999999);
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
