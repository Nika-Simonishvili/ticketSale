<?php

namespace App\Services;

use App\Contracts\OrderRepositoryContract;
use App\Contracts\TicketRepositoryContract;
use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\Order;

class OrderService
{
    public function __construct(
        private readonly TicketRepositoryContract $ticketRepository,
        private readonly OrderRepositoryContract $orderRepository
    ) {
    }

    public function store(array $data): Order
    {
        $tickets = $this->ticketRepository->findWithIds($data['ids']);

        /** @var Event $event */
        $event = $tickets->last()->event;

        return \DB::transaction(function () use ($event, $tickets, $data) {
            $order = $this->orderRepository->store($data, $tickets, $event);

            $event->decrement('available_tickets_quantity', $tickets->count());

            $this->orderRepository->attachTicketsToOrder($order, $tickets);

            return $order;
        });
    }

    public function cancel(Order $order): void
    {
        abort_if($order->status === OrderStatus::SUCCESS->value, 403, 'Can not cancel order, it`s completed.');

        $this->orderRepository->cancel($order);
    }
}
