<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCycle extends Model
{
    use HasFactory;
    protected $table = 'subscribtion_cycles';


    public function products()
    {
        return $this->morphedByMany(Product::class, 'cycleable');
    }

    public function durations()
    {
        return $this->belongsToMany(SubscribtionDuration::class, 'duration_cycles', 'cycle_id');
    }


    
    public function packages()
    {
        return $this->morphedByMany(Package::class, 'cycleable');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'cycle_id');
    }

    // public function subs()
    // {
    //     return $this->hasMany(Subsc::class, 'cycle_id');
    // }
}
