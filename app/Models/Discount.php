<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    public function variants()
    {
        return $this->morphedByMany(Variant::class, 'discountable');
    }

    public function packages()
    {
        return $this->morphedByMany(Package::class, 'discountable');
    }
}
