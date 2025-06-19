<?php

namespace App\Models;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function emails()
    {
        return $this->belongsToMany(Email::class, 'email_config_email')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
