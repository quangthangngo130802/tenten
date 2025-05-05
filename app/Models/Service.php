<?php

namespace App\Models;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
     protected $guarded = [];
    public $timestamps = true;

    public function os()
    {
        return $this->belongsTo(Os::class, 'os_id', 'id');
    }

    public function hosting()
    {
        return $this->belongsTo(Hosting::class, 'product_id', 'id');
    }

    public function cloud()
    {
        return $this->belongsTo(Cloud::class, 'product_id', 'id');
    }

    public function emailServer()
    {
        return $this->belongsTo(Email::class, 'product_id', 'id');
    }
}
