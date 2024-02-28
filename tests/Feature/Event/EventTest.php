<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Ticket;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventTest extends TestCase
{
    #[Test]
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

    #[Test]
    public function test_it_creates_event(): void
    {
        $this->login();
        $eventCategory = EventCategory::factory()->create();
        $eventTitle = fake()->title;

        $data = [
            'title' => $eventTitle,
            'start_date_time' => now()->addDay()->format('Y-m-d H:i'),
            'address' => fake()->address,
            'tickets_quantity' => 10,
            'available_tickets_quantity' => 10,
            'event_category_id' => $eventCategory->id,
        ];

        $response = $this->postJson(self::API_URL.'events', $data);

        $response->assertOk();
        $this->assertDatabaseCount('events', 1);
        $this->assertDatabaseHas('events', ['title' => $eventTitle]);
    }

    #[Test]
    public function test_it_updates_event(): void
    {
        $this->login();
        $event = Event::factory()->create();
        $eventCategory = EventCategory::factory()->create();
        $updatedTitle = fake()->title;

        $data = [
            'title' => $updatedTitle, 'start_date_time' => now()->addDay()->format('Y-m-d H:i'),
            'address' => fake()->address,
            'tickets_quantity' => 10,
            'available_tickets_quantity' => 10,
            'event_category_id' => $eventCategory->id,
        ];

        $response = $this->patchJson(self::API_URL.'events/'.$event->id, $data);

        $response->assertOk();
        $this->assertDatabaseCount('events', 1);
        $this->assertDatabaseHas('events', ['title' => $updatedTitle]);
    }

    #[Test]
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

    #[Test]
    public function test_it_shows_single_event(): void
    {
        $this->login();
        $event = Event::factory()->create();

        $response = $this->getJson(self::API_URL."events/{$event->id}");

        $response->assertOk();
        $response->assertJsonStructure(
            [
                'success',
                'event',
            ],
        );
        $this->assertDatabaseCount('events', 1);
        $this->assertDatabaseHas('events', $event->toArray());
    }

    #[Test]
    public function test_it_shows_available_tickets_for_event(): void
    {
        $this->login();
        $event = Event::factory()->create();
        Ticket::factory()->times(2)->create(['event_id' => $event->id, 'available' => true]);
        Ticket::factory()->times(1)->create(['event_id' => $event->id, 'available' => false]);

        $response = $this->getJson(self::API_URL."events/available-tickets/{$event->id}");

        $response->assertOk();
        $response->assertJsonStructure(
            [
                'success',
                'tickets',
            ],
        );

        $this->assertCount(2, $response['tickets']);
        $this->assertEquals(2, Ticket::where('available', true)->count());
    }

    #[Test]
    public function test_it_deletes_event(): void
    {
        $this->login();
        $event = Event::factory()->create();

        $response = $this->deleteJson(self::API_URL.'events/'.$event->id);

        $response->assertOk();
        $this->assertSoftDeleted('events', $event->toArray());
    }
}
