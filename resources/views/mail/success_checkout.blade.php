<div>
    <span>"You have successfully purchased tickets for {{$order->event->title}}"</span>
    tickets:
    <br>
    @foreach($order->tickets as $ticket)
        seat number: {{$ticket->seat_number}}
        QR:      <img src="{{ asset('storage/' . $ticket->qr_code) }}" alt="Ticket QR Code">
    @endforeach
    <footer>
        Thank you for using our application!
    </footer>
</div>