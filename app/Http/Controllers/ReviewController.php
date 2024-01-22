<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reviews\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Event;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();
        $event = Event::find($data['event_id']);

        $review = $event->reviews()->create(\Arr::except($data, 'event_id'));
        $review->load('user');

        return Response::success([
            'message' => 'Review saved.',
            'review' => ReviewResource::make($review),
        ]);
    }
}
