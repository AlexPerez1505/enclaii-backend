<?php

namespace App\Mail;

use App\Models\EstudioArchivo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GalleryImageShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly EstudioArchivo $archivo,
        public readonly User $sender,
        public readonly string $subjectLine,
        public readonly string $messageBody,
        public readonly string $imageUrl,
        public readonly string $downloadName,
    ) {}

    public function build(): self
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->subjectLine)
            ->replyTo($this->sender->email, $this->sender->name)
            ->view('emails.gallery-image-share');
    }
}
