<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessCheckoutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("Ypu have successfully purchased tickets for {$this->order->event->title}")
            ->line("tickets: {$this->order->tickets->pluck('seat_number')}")
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
