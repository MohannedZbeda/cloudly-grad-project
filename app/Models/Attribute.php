<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function values() {
        return $this->hasMany(Value::class);
    }

    public function productValues() {
        return $this->hasMany(ProductValue::class);
    }
}
