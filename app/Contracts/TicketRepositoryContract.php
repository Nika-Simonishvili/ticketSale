<?php

namespace App\Contracts;

use App\Models\Ticket;
use Illuminate\Support\Collection;

interface TicketRepositoryContract
{
    public function findWithIds(array $ids): ?Collection;

    public function update(Ticket $ticket, array $data): Ticket;
}
