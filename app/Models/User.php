<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    
    
    
    public function info() {
        return $this->hasOne(UserInfo::class);
    }

    public function wallet() {
        return $this->hasOne(Wallet::class);
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

    public function testimonies() {
        return $this->hasMany(Testimony::class);
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
