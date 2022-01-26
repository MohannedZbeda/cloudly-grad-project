<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Variant extends Model
{
    use HasFactory;
    public static function getPrice($id, $attributes) {
        $total = 0;
        $product_attributes = CustomAttribute::where('product_id', $id)->get();
        foreach ($attributes as $attribute) {
            $custom_attribute = $product_attributes->where('attribute_id', $attribute->attribute_id)->first();
            $total +=  $custom_attribute->custom_price * $attribute->value;
        }
        return $total;
      }
    public function getDiscount()
    { 
        $new_price = $this->price -  ($this->price * ($this->discount_percentage / 100));        
        return $new_price;    
    }


    public function invoice()
    {
        return $this->morphOne(InvoiceItem::class, 'invoiceable');
    }

    public function cycles()
    {
        return $this->morphToMany(SubscriptionCycle::class, 'cycleable');
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
