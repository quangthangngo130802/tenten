<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'status',
        'approved_at',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Người duyệt (admin)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
