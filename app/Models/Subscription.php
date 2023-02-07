<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function requests()
    {
        return $this->hasMany(CustomerRequest::class, 'sub_id');
    }
    public function cycle()
    {
        return $this->belongsTo(SubscriptionCycle::class);
    }

    public function getExpiryDate() {
        $date = new Carbon($this->created_at);
        return $date->addMonths($this->cycle->months)->toDateString();

    }

}
