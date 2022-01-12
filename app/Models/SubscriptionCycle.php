<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCycle extends Model
{
    use HasFactory;
    protected $table = 'subscribtion_cycles';


    public function variants()
    {
        return $this->morphedByMany(Variant::class, 'cycleable');
    }

    
    public function packages()
    {
        return $this->morphedByMany(Package::class, 'cycleable');
    }
}
