<?php

namespace App\Models;

use App\Models\ZnsMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserZalo extends Model
{
    use HasFactory;
    protected $table = 'user_zalos';
    protected $guarded = [];

    public $timestamps = true;

    public function znsMessages()
    {
        return $this->hasMany(ZnsMessage::class, 'user_id');
    }


    // Đếm số tin nhắn
    public function messageCount()
    {
        return $this->znsMessages()->count();
    }
}
