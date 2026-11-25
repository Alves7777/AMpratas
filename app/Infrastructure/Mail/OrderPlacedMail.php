<?php

namespace App\Infrastructure\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class OrderPlacedMail extends Mailable
{
    public function __construct(public readonly Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pedido {$this->order->order_number} recebido — AMpratas",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.orders.placed',
        );
    }
}
