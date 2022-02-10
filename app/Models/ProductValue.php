<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductValue extends Model
{
    use HasFactory;
    protected $table = 'product_values';
    protected $fillable = array('variant_id', 'attribute_id', 'value_id');
    
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function attribute() {
        return $this->belongsTo(Attribute::class);
    }

    public function value() {
        return $this->belongsTo(Value::class);
    }
}
