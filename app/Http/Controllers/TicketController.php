<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\TicketImportRequest;
use App\Imports\TicketsImport;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    public function importTickets(int $eventId, TicketImportRequest $request)
    {
        $event = Event::find($eventId);
        Excel::import(new TicketsImport($event), $request->file('file')->store('tickets'));

        return Response::success([
            'message' => 'Tickets imported.',
        ]);
    }

    public function show(Ticket $ticket)
    {
        //
    }

    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    public function destroy(Ticket $ticket)
    {
        //
    }
}
