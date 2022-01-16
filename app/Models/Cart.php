<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;


    public function getTotal()
    {   $total = 0;
        foreach($this->items as $item) {
          $total+= $item->cartable->getDiscount() * $item->quantity;
        }
        return $total;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
