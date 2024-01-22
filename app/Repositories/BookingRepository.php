<?php

namespace App\Repositories;

use App\Contracts\BookingRepositoryContract;
use App\Enums\BookingStatus;
use App\Enums\OrderStatus;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Ticket;

class BookingRepository implements BookingRepositoryContract
{
    public function store(array $data): Booking
    {
        return Booking::create($data);
    }

    public function handleModelsUpdatesForSuccessCheckout(Order $order): void
    {
        \DB::transaction(function () use ($order) {
            $order->update(['status' => OrderStatus::SUCCESS]);
            $order->booking->update(['status' => BookingStatus::COMPLETED]);

            $order->event->update(['total_income' => $order->event->total_income + $order->tickets->sum('price')]);
        });
    }

    public function cancelBooking(Booking $booking): void
    {
        \DB::transaction(function () use ($booking) {
            $bookingTickets = $booking->order->tickets;
            $bookingTickets->each(function (Ticket $ticket) {
                $ticket->update(['available' => true]);
            });

            $ticketsSumPrice = $bookingTickets->sum('price');
            $booking->order->event->decrement('total_income', $ticketsSumPrice);

            $booking->order->tickets()->detach();
            $booking->order()->delete();
            $booking->delete();
        });
    }
}
