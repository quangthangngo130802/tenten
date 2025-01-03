<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewService extends Model
{
    use HasFactory;
    protected $table = 'renewservice';
     protected $guarded = [];
    public $timestamps = true;

    public function os()
    {
        return $this->belongsTo(Os::class, 'os_id', 'id');
    }
}
