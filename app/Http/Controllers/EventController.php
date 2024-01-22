<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\Event\EventResource;
use App\Http\Resources\TicketResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function index()
    {
        return Response::success([
            'events' => EventResource::collection(Event::with(['creator', 'category'])->paginate(5))->resource,
        ]);
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = \Auth::user()->events()->create($request->validated());

        return Response::success([
            'event' => EventResource::make($event->load('creator')),
        ]);
    }

    public function show(Event $event)
    {
        return Response::success([
            'event' => EventResource::make($event->load('creator', 'category')),
        ]);
    }

    public function availableTickets(Event $event)
    {
        $data = $event->tickets()->available()->get();

        return Response::success([
            'tickets' => TicketResource::collection($data),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        tap($event)->update($request->validated());

        return Response::success([
            'event' => EventResource::make($event->load('creator')),
        ]);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return Response::success(['message' => 'event deleted.']);
    }
}
