<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $table = 'wards';
     protected $guarded = [];
    public $timestamps = true;

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
