<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cloud extends Model
{
    use HasFactory;
    protected $table = 'cloudservices';
     protected $guarded = [];
    public $timestamps = true;
}
