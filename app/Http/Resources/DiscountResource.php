<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class DiscountResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'discount_amount' => $this->discount_amount,
          'discount_percentage' => $this->discount_percentage,
          'created_at' => $this->created_at->toDateString(),
          'end_date' => Carbon::parse($this->end_date)->toDateString(),
          'products' => ProductResource::collection($this->whenLoaded('variants')),
          'packages' => PackageResource::collection($this->whenLoaded('packages'))
        ];
    }
}
