<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    
    public function getDiscount()
    { 
        $new_price = $this->price -  ($this->price * ($this->discount_percentage / 100));        
        return $new_price;    
    }


    public function invoice()
    {
        return $this->morphOne(InvoiceItem::class, 'invoiceable');
    }

    public function carts()
    {
        return $this->morphMany(CartItem::class, 'cartable');
    }

    public function values() {
        return $this->hasMany(ProductValue::class);
    }

    public function packages() {
        return $this->belongsToMany(Package::class, 'package_products');
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }


    public function subscriptions()
    {
        return $this->morphToMany(Subscription::class, 'subscribeable');
    }

    public function vouchers()
    {
        return $this->morphMany(Voucher::class, 'voucherable');
    }

    public function getVouchers()
    {    $vouchers = [];
         foreach ($this->vouchers->where('used', false) as $voucher) {
             array_push($vouchers, $voucher->code);
         }
         $vouchers = implode(', ', $vouchers);
         return $vouchers;
    }
    
}
