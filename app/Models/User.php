<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_id',
        'shop_name',
        'plan_name'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'created_at', 'updated_at',
    ];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function appShop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
