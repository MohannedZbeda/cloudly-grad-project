<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    public function variants() {
        return $this->hasMany(Variant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cycles()
    {
        return $this->morphToMany(SubscriptionCycle::class, 'cycleable');
    }
    
    public function customAttributes()
    {
        return $this->hasMany(CustomAttribute::class);
    }
    
}
