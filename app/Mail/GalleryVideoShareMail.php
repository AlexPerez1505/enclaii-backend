<?php

namespace App\Mail;

use App\Models\EstudioArchivo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GalleryVideoShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly EstudioArchivo $archivo,
        public readonly User $sender,
        public readonly string $subjectLine,
        public readonly string $messageBody,
        public readonly string $videoUrl,
        public readonly string $downloadName,
    ) {}

    public function build(): self
    {
        return $this
            ->from($this->sender->email, $this->sender->name)
            ->subject($this->subjectLine)
            ->replyTo($this->sender->email, $this->sender->name)
            ->view('emails.gallery-video-share');
    }
}
