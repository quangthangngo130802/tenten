<?php

namespace App\Models;

use App\Models\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $table = 'provinces';
     protected $guarded = [];
    public $timestamps = true;

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
