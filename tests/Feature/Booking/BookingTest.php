<?php

namespace Tests\Feature\Booking;

use App\Enums\BookingStatus;
use App\Enums\OrderStatus;
use App\Models\Booking;
use App\Models\Order;
use App\Services\StripeService;
use Mockery\MockInterface;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_creates_booking(): void
    {
        $this->login();

        $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

        $this->partialMock(StripeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('createBooking')->once();
        });

        $response = $this->postJson(self::API_URL . 'booking/checkout', ['orderId' => $order->id]);

        $response->assertOk();
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas(
            'bookings',
            [
                'order_id' => $order->id,
                'status'   => BookingStatus::PENDING,
            ]
        );
    }

    /**
     * @test
     */
    public function test_successful_checkout(): void
    {
        \Notification::fake();
        $this->login();

        $sessionId = \Str::random();
        $order = Order::factory()->create(['session_id' => $sessionId]);
        Booking::factory()->create(['order_id' => $order->id]);

        $this->partialMock(StripeService::class, function (MockInterface $mock) use ($sessionId) {
            $mock->shouldReceive('retrieveSession')
                ->with($sessionId)
                ->once();
        });

        $response = $this->getJson(self::API_URL . 'booking/callback/success?session_id=' . $sessionId);

        $response->assertOk();
        $response->assertExactJson(
            [
                'success' => true,
                'message' => 'Successful Checkout.',
            ]
        );
    }
}
