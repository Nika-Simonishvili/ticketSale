<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start_date_time' => $this->start_date_time,
            'address' => $this->address,
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'category' => EventCategoryResource::make($this->whenLoaded('category')),
        ];
    }
}
