<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    
    
    public function getDiscount()
    { 
        $new_price = $this->price -  ($this->price * ($this->discount_percentage / 100));        
        return $new_price;    
    }


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

    public function vouchers()
    {
        return $this->morphMany(Voucher::class, 'voucherable');
    }

    public function cycles()
    {
        return $this->morphToMany(SubscriptionCycle::class, 'cycleable');
    }

    public function variants() {
        return $this->belongsToMany(Variant::class, 'package_products');
    }

    public function getVouchers()
    {    $vouchers = [];
         foreach($this->vouchers->where('used', false) as $voucher) {
             array_push($vouchers, $voucher->code);
         }
         $vouchers = implode(', ', $vouchers);
         return $vouchers;
    }
}
