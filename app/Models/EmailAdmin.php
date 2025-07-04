<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAdmin extends Model
{
    use HasFactory;

    protected $table = 'emails_admin';
    protected $guarded = [];
    public $timestamps = true;
}
