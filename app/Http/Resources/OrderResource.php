<?php

namespace App\Http\Resources;

use App\Http\Resources\Event\EventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'total_price' => $this->total_price,
            'user' => UserResource::make($this->whenLoaded('user')),
            'event' => EventResource::make($this->whenLoaded('event')),
        ];
    }
}
