<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    
    
    
    public function info() {
        return $this->hasOne(UserInfo::class);
    }

    public function wallets() {
        return $this->hasMany(Wallet::class);
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }

    public function cart() {
        return $this->hasOne(Cart::class);
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }
    
    
    
    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
