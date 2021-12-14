<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    public function getDiscounts()
    {
      $discounts = [];
      $new_price = $this->price;
      foreach ($this->discounts as $discount) {
        $new_price = $new_price -  $discount->discount_amount;
        $new_price= $new_price -  ($new_price * ($discount->discount_percentage / 100));
    
        if($discount->discount_amount)
           array_push($discounts, $discount->discount_amount. 'LYD');
           else 
           array_push($discounts, $discount->discount_percentage. '%');
      }
      return [
          'new_price' => $new_price,
          'discounts' => $discounts
      ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable');
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
