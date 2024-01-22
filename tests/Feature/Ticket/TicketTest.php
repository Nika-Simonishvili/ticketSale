<?php

namespace Tests\Feature\Ticket;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class TicketTest extends TestCase
{
    /**
     * @test
     */
    public function teest_it_shows_available_tickets_for_event(): void
    {
        $this->login();

        $availableTicketCount = 5;
        $nonAvailableTicketCount = 4;
        $event = Event::factory()->create();
        Ticket::factory()->count($availableTicketCount)->create(['event_id' => $event->id, 'available' => true]);
        Ticket::factory()->count($nonAvailableTicketCount)->create(['event_id' => $event->id, 'available' => false]);

        $response = $this->getJson(self::API_URL.'events/available-tickets/'.$event->id);

        $response->assertOk();
        $response->assertJsonCount($availableTicketCount, 'tickets');
    }

    /**
     * @test
     */
    public function test_ticket_import()
    {
        Excel::fake();
        $this->login();

        $event = Event::factory()->create();
        $file = UploadedFile::fake()->create(base_path('tests/Fixtures/tickets_test.xlsx'));

        $response = $this->postJson(
            self::API_URL.'tickets/'.$event->id.'/import-tickets',
            [
                'file' => $file,
            ]
        );

        $response->assertOk();
        $response->assertExactJson(
            [
                'success' => true,
                'message' => 'Tickets imported.',
            ]
        );

    }
}
