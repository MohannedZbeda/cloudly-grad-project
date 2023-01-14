<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Package extends Model
{
    use HasFactory;


    public function subscriptions()
    {
        return $this->morphToMany(Subscription::class, 'subscribeable');
    }

    public function carts()
    {
        return $this->morphMany(CartItem::class, 'cartable');
    }

    public function invoice()
    {
        return $this->morphOne(InvoiceItem::class, 'invoiceable');
    }

    public function cycles()
    {
        return $this->morphToMany(SubscriptionCycle::class, 'cycleable');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'package_products');
    }
}
