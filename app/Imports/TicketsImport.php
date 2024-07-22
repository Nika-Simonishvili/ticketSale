<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TicketsImport implements ToCollection, WithCalculatedFormulas, WithHeadingRow
{
    public function __construct(private readonly Event $event)
    {
    }

    public function collection(Collection $collection): void
    {
        abort_if($collection->count() !== $this->event->tickets_quantity, 422, 'invalid tickets quantity');

        foreach ($collection as $ticket) {
            $ticket->toArray();
            Ticket::create([
                'event_id' => $this->event->id,
                'price' => $ticket['price'],
                'row_number' => $ticket['row_number'],
                'seat_number' => $ticket['seat_number'],
                'type' => $ticket['type'],
            ]);
        }
    }
}
