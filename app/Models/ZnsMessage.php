<?php

namespace App\Models;

use App\Models\ZaloOa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZnsMessage extends Model
{
    use HasFactory;
    // protected $table = 'zns_messages';

    protected $table = 'sgo_zns_messages';
    protected $guarded = [];

    public function zaloOa()
    {
        return $this->belongsTo(ZaloOa::class, 'oa_id');
    }

    public function template()
    {
        return $this->belongsTo(OaTemplate::class, 'template_id');
    }
}
