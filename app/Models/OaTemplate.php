<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OaTemplate extends Model
{
    use HasFactory;
    // protected $table = 'oa_templates';
    protected $table = 'sgo_oa_templates';
    protected $guarded = [];
}
