<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    use HasFactory;
    protected $table_name = "customer_requests"; 
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function sub()
    {
        return $this->belongsTo(Subscription::class, 'sub_id');
    }

}
