<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketResuelto extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Ticket $ticket)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket resuelto: ' . $this->ticket->subject,
        );
    }

    public function content(): Content
    {
        $typeLabels = [
            'problema_corregido' => 'Problema corregido',
            'configuracion_realizada' => 'Configuración realizada',
            'error_usuario' => 'Error del usuario',
            'capacitacion' => 'Capacitación',
            'incidencia_externa' => 'Incidencia externa',
            'otro' => 'Otro',
        ];

        return new Content(
            view: 'emails.ticket-resuelto',
            with: [
                'ticket' => $this->ticket,
                'typeLabel' => $typeLabels[$this->ticket->resolution_type] ?? 'Resuelto',
            ],
        );
    }
}
