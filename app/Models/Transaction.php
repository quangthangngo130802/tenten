<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    // protected $table = 'transactions';

    protected $table = 'sgo_transactions';
    protected $guarded = [];

    public static function totalSuccessAmount()
    {
        return self::where('status', 1)->sum('amount');
    }
    public function zaloUser()
    {
        return $this->belongsTo(UserZalo::class, 'user_id');
    }
}
