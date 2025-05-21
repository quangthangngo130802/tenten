<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $guarded = [];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function ward1()
    {
        return $this->belongsTo(Ward::class, 'ward', 'id');
    }

    public function district1()
    {
        return $this->belongsTo(District::class,  'district', 'id');
    }

    public function province1()
    {
        return $this->belongsTo(Province::class,  'province', 'id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class,  'user_id', 'id');
    }
}
