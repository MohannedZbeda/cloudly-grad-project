<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function variants()
    {
        return $this->morphedByMany(Variant::class, 'subscribeable');
    }
    public function requests()
    {
        return $this->hasMany(CustomerRequest::class, 'sub_id');
    }

    public function packages()
    {
        return $this->morphedByMany(Package::class, 'subscribeable');
    }
}
