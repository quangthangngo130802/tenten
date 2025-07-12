<?php

namespace App\Models;

use App\Models\UserZalo;
use App\Models\ZnsMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZaloOa extends Model
{
    use HasFactory;
    // protected $table = 'zalo_oas';
    protected $table = 'sgo_zalo_oas';
    protected $guarded = [];

    public function znsMessages()
    {
        return $this->hasMany(ZnsMessage::class, 'oa_id');
    }

    public function userZalo()
    {
        return $this->belongsTo(UserZalo::class, 'user_id');
    }

    // Đếm số tin nhắn
    public function messageCount()
    {
        return $this->znsMessages()->count();
    }
}
