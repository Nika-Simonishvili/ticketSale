<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripeService
{
    public function createBooking(Order $order): Session
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $lineItems = [];

        foreach ($order->tickets as $ticket) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'ticket: '.$ticket->id.'-'.$ticket->seat_number,
                    ],
                    'unit_amount' => $ticket->price * 100,
                ],
                'quantity' => 1,
            ];
        }

        return $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);
    }

    public function retrieveSession(string $sessionId): Session
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        return $stripe->checkout->sessions->retrieve($sessionId);
    }
}
