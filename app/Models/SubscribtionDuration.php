<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribtionDuration extends Model
{
    use HasFactory;

    public function cycles()
    {
        return $this->belongsToMany(SubscriptionCycle::class, 'duration_cycles', 'duration_id');
    }
}
