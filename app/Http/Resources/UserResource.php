<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Wallet;

class UserResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'info' => $this->whenLoaded('info'),
            'role' => $this->roles[0]->display_name,
            'role_id' => $this->roles[0]->id,
            'state' => $this->state,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
