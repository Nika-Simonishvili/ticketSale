<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'user' => UserResource::make($this->whenLoaded('user')),
            'reviewable_type' => $this->reviewable_type,
            'reviewable_id' => $this->reviewable_id,
        ];
    }
}
