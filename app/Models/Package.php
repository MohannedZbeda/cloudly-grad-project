<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
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
    
    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    public function vouchers()
    {
        return $this->morphMany(Voucher::class, 'voucherable');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'package_products');
    }

}
