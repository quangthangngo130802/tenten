<?php

namespace App\Models;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigBank extends Model
{
    use HasFactory;
    protected $table = 'config_banks';

    protected $guarded = [];

    public $timestamps = true;

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function bankcompany()
    {
        return $this->belongsTo(Bank::class, 'company_bank_id');
    }
}
