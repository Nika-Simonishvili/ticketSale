<?php

namespace App\Http\Resources;

use App\Http\Resources\Event\EventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'event' => EventResource::make($this->whenLoaded('event')),
        ];
    }
}
