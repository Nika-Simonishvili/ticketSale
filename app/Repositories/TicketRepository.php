<?php

namespace App\Repositories;

use App\Contracts\TicketRepositoryContract;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketRepository implements TicketRepositoryContract
{
    public function findWithIds(array $ids): ?Collection
    {
        return Ticket::with('event')->whereIn('id', $ids)->get();
    }
}
