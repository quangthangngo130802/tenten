<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $table = 'emails';
    protected $guarded = [];
    public $timestamps = true;

    public function emailConfigs()
    {
        return $this->belongsToMany(EmailConfig::class, 'email_config_email')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
