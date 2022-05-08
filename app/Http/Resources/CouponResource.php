<?php

namespace App\Http\Resources;

use App\Models\Coupon;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    
    public function toArray($request)
    {   $codes = Coupon::where('start_date', $this->start_date)
        ->where('end_date', $this->end_date)
        ->where('discount_percentage', $this->discount_percentage);
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'discount_percentage' => $this->discount_percentage,
            'codes' => implode(', ', $codes->pluck('code')->toArray()),
            'amount' => $codes->count(),
            'used_amount' => $codes->where('used', 1)->count()
            
        ];
    }
}
