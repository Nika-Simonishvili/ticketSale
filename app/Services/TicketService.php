<?php

namespace App\Services;

use App\Contracts\TicketRepositoryContract;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    public function __construct(
        private readonly TicketRepositoryContract $ticketRepository,
    ) {
    }

    public function generateQr(Collection $tickets): void
    {
        $tickets->each(function (Ticket $ticket) {
            $qr = QrCode::format('png')
                ->size(200)->errorCorrection('H')
                ->generate('A simple example of QR code!');
            $qrPath = 'public/tickets/qr'.now().'.png';
            \Storage::disk('public')->put($qrPath, $qr);

            $this->ticketRepository->update($ticket, ['qr_code' => $qrPath]);
        });
    }
}
