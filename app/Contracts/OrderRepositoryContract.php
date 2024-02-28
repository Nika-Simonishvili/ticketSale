<?php

namespace App\Contracts;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryContract
{
    public function store(array $data, Collection $tickets, Event $event): Order;

    public function attachTicketsToOrder(Order $order, Collection $tickets): void;

    public function detachTicketsToOrder(Order $order): void;

    public function find(int $id): Order;

    public function findOrderWithSessionId(string $sessionId): ?Order;

    public function update(Order $order, array $data): Order;

    public function cancel(Order $order): void;
}
