<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'customer_info';
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }
}
