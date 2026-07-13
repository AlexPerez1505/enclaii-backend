<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnuncioPublicado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly \App\Models\Anuncio $anuncio)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo anuncio: ' . $this->anuncio->titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.anuncio-publicado',
            with: ['anuncio' => $this->anuncio],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
