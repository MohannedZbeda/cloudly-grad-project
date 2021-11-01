<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    
    
    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

    public function vouchers()
    {
        return $this->morphMany(Voucher::class, 'voucherable');
    }

    public function variants() {
        return $this->hasMany(Variant::class);
    }

}
