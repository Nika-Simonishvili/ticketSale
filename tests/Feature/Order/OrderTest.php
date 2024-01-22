<?php

namespace Tests\Feature\Order;

use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_stores_order(): void
    {
        $this->login();

        $event = Event::factory()->create(['available_tickets_quantity' => 2]);

        $tickets = Ticket::factory(2)->create(
            [
                'event_id' => $event->id,
                'available' => true,
            ],
        );

        $data = ['ids' => [$tickets->last()->value('id')]];

        $response = $this->postJson(self::API_URL.'orders', $data);

        $response->assertOk();
        $response->assertJsonStructure(
            [
                'success',
                'order' => [
                    'status',
                    'total_price',
                ],
                'message',
            ]
        );

        $this->assertDatabaseHas(
            'events',
            [
                'id' => $event->id,
                'available_tickets_quantity' => 1,
            ]
        );

        $this->assertDatabaseHas(
            'tickets',
            [
                'id' => $tickets->last()->value('id'),
                'available' => false,
            ]
        );

        $this->assertDatabaseCount('order_ticket', 1);
    }

    /**
     * @test
     */
    public function test_cant_cancel_success_order(): void
    {
        $this->login();

        $order = Order::factory()->create(['status' => OrderStatus::SUCCESS->value]);

        $response = $this->patchJson(self::API_URL.'orders/cancel/'.$order->id);

        $response->assertForbidden();
        $response->assertJsonFragment(['message' => 'Can not cancel order, it`s completed.']);
    }

    /**
     * @test
     */
    public function test_order_cancel(): void
    {
        $this->login();

        $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

        $response = $this->patchJson(self::API_URL.'orders/cancel/'.$order->id);

        $response->assertOk();
        $response->assertExactJson(
            [
                'success' => true,
                'message' => 'Order canceled.',
            ]
        );

        $this->assertDatabaseHas('orders', ['status' => OrderStatus::CANCEL->value]);
    }
}
