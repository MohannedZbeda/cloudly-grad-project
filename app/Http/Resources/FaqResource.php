<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_question' => $this->ar_question,
            'ar_answer' => $this->ar_answer,
            'en_question' => $this->en_question,
            'en_answer' => $this->en_answer,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
