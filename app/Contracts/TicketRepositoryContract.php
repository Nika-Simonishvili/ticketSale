<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface TicketRepositoryContract
{
    public function findWithIds(array $ids): ?Collection;
}
