<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductValue extends Model
{
    use HasFactory;
    protected $table = 'product_values';
    protected $fillable = array('product_id', 'attribute_id', 'value');
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute() {
        return $this->belongsTo(Attribute::class);
    }
}
