<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }
}
