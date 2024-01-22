<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\EventCategory;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_should_throw_unauthorized(): void
    {
        $response = $this->postJson(self::API_URL.'events');

        $response->assertUnauthorized();
    }

    /**
     * @test
     *
     * @dataProvider wrongDataForCreate
     */
    public function test_it_should_throw_validation_on_create_event($requestData): void
    {
        $this->login();
        EventCategory::factory()->create(['id' => 12345]);

        $response = $this->postJson(self::API_URL.'events', $requestData);

        $response->assertStatus(422);
    }

    /**
     * @dataProvider
     *
     * @see test_it_should_throw_validation_on_create_event
     */
    public static function wrongDataForCreate(): array
    {
        $ticketsQuantity = rand(10, 20);

        return [
            'start_date_time_past' => [
                [
                    'title' => fake()->title,
                    'start_date_time' => now()->subDay(),
                    'address' => fake()->address,
                    'tickets_quantity' => $ticketsQuantity,
                    'available_tickets_quantity' => $ticketsQuantity,
                    'event_category_id' => 12345,
                ],
            ],
            'non-existing-eventCategoryId' => [
                [
                    'title' => fake()->title,
                    'start_date_time' => now(),
                    'address' => fake()->address,
                    'tickets_quantity' => $ticketsQuantity,
                    'available_tickets_quantity' => $ticketsQuantity,
                    'event_category_id' => 1234,
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function test_it_shows_events_list(): void
    {
        $this->login();
        $events = Event::factory()->count(10)->create();

        $response = $this->getJson(self::API_URL.'events');

        $response->assertOk();
        $response->assertJsonStructure(
            [
                'success',
                'events' => [
                    'current_page',
                    'data',
                ],
            ],
        );
        $this->assertDatabaseCount('events', 10);
        $this->assertDatabaseHas('events', $events->first()->toArray());
    }

    /**
     * @test
     */
    public function test_it_deletes_event(): void
    {
        $this->login();
        $event = Event::factory()->create();

        $response = $this->deleteJson(self::API_URL.'events/'.$event->id);

        $response->assertOk();
        $this->assertSoftDeleted('events', $event->toArray());
    }
}
