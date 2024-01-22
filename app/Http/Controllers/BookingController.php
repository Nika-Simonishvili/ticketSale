<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookingService)
    {
    }

    public function bookTickets(BookingRequest $request): JsonResponse
    {
        $checkoutSessionURL = $this->bookingService->bookTickets($request->validated());

        return Response::success([
            'stripeSessionId' => $checkoutSessionURL->url,
        ]);
    }

    public function checkoutSuccess(Request $request): JsonResponse
    {
        $this->bookingService->checkoutSuccess($request);

        return Response::success([
            'message' => 'Successful Checkout.',
        ]);
    }

    public function checkoutFail(Request $request): JsonResponse
    {
        $this->bookingService->checkoutFail($request);

        return Response::success([
            'message' => 'Failed Checkout. Please, try again.',
        ]);
    }

    public function cancelBooking(Booking $booking): JsonResponse
    {
        $this->bookingService->cancelBooking($booking);

        return Response::success([
            'message' => 'Booking canceled.',
        ]);
    }
}
