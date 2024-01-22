<?php

namespace App\Contracts;

use App\Models\Booking;
use App\Models\Order;

interface BookingRepositoryContract
{
    public function store(array $data): Booking;

    public function handleModelsUpdatesForSuccessCheckout(Order $order): void;

    public function cancelBooking(Booking $booking): void;
}
