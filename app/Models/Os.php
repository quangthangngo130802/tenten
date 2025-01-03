<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Os extends Model
{
    use HasFactory;
    protected $table = 'os';
     protected $guarded = [];
    public $timestamps = true;
}
