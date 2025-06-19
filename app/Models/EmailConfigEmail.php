<?php

namespace App\Models;

use App\Models\Email;
use App\Models\EmailConfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfigEmail extends Model
{
    use HasFactory;
    protected $table = 'email_config_email';
    protected $guarded = [];
    public $timestamps = true;

    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    public function emailConfig()
    {
        return $this->belongsTo(EmailConfig::class);
    }
}
