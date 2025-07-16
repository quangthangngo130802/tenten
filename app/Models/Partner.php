<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = true;

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

}
