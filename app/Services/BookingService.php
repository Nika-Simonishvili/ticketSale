<?php

namespace App\Services;

use App\Contracts\BookingRepositoryContract;
use App\Contracts\OrderRepositoryContract;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Notifications\CancelBookingNotification;
use App\Notifications\SuccessCheckoutNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingService
{
    public function __construct(
        private readonly BookingRepositoryContract $bookingRepository,
        private readonly OrderRepositoryContract $orderRepository,
        private readonly TicketService $ticketService,
        private readonly StripeService $stripeService,
    ) {
    }

    public function bookTickets(array $request): Session
    {
        $order = $this->orderRepository->find($request['orderId']);

        $checkoutSession = $this->stripeService->createBooking($order);

        $this->orderRepository->update($order, ['session_id' => $checkoutSession->id]);

        $this->bookingRepository->store([
            'user_id' => \Auth::id(),
            'order_id' => $order->id,
            'status' => BookingStatus::PENDING,
        ]);

        return $checkoutSession;
    }

    public function checkoutSuccess(Request $request): void
    {
        $sessionId = $request->get('session_id');

        try {
            $session = $this->stripeService->retrieveSession($sessionId);

            throw_if(! $session, new NotFoundHttpException('Invalid session id.'));

            $order = $this->orderRepository->findOrderWithSessionId($sessionId);

            throw_if(! $order, new NotFoundHttpException('Order with this session id was not found.'));

            $this->bookingRepository->handleModelsUpdatesForSuccessCheckout($order);
            $this->ticketService->generateQr($order->tickets);

            Notification::send($order->booking->user, new SuccessCheckoutNotification($order));
        } catch (NotFoundHttpException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }

    public function checkoutFail(Request $request): void
    {
        //
    }

    public function cancelBooking(Booking $booking): void
    {
        // TODO add validations

        $booking->load(['user', 'order.tickets']);
        $user = $booking->user;

        $this->bookingRepository->cancelBooking($booking);

        $user->notify(new CancelBookingNotification());
    }
}
