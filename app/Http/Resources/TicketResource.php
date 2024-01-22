<?php

namespace App\Http\Resources;

use App\Http\Resources\Event\EventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'row_number' => $this->row_number,
            'seat_number' => $this->seat_number,
            'type' => $this->type,
            'available' => $this->available,
            'event' => EventResource::make($this->whenLoaded('event')),
        ];
    }
}
