<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryContract;
use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryContract
{
    public function store(array $data, Collection $tickets, Event $event): Order
    {
        $totalPrice = $tickets->sum('price');

        return \Auth::user()->orders()->create([
            'status' => OrderStatus::PENDING,
            'total_price' => $totalPrice,
            'event_id' => $event->id,
        ]);
    }

    public function attachTicketsToOrder(Order $order, Collection $tickets): void
    {
        $order->tickets()->attach($tickets->pluck('id'));

        $tickets->each(function (Ticket $ticket) {
            $ticket->update(['available' => false]);
        });
    }

    public function find(int $id): Order
    {
        return Order::with('tickets')->find($id);
    }

    public function findOrderWithSessionId(string $sessionId): ?Order
    {
        return Order::with(['booking', 'tickets'])
            ->where('session_id', $sessionId)
            ->where('status', OrderStatus::PENDING)
            ->first();
    }

    public function update(Order $order, array $data): Order
    {
        return tap($order)->update($data);
    }

    public function cancel(Order $order): void
    {
        \DB::transaction(function () use ($order) {
            $order->tickets()->detach();
            $order->update(['status' => OrderStatus::CANCEL]);
        });
    }
}
