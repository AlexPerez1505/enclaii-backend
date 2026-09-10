<?php

namespace App\Mail;

use App\Models\Estudio;
use App\Models\User;
use App\Services\ReportPdfGenerator;
use App\Services\StudyImageAttachmentBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class StudyShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Estudio $estudio,
        public readonly User $sender,
        public readonly string $subjectLine,
        public readonly string $messageBody,
        public readonly Collection $imagenes,
        public readonly Collection $videos,
        public readonly Collection $reportes,
    ) {}

    public function build(): self
    {
        $mail = $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->subjectLine)
            ->replyTo($this->sender->email, $this->sender->name)
            ->view('emails.study-share');

        $imageAttachmentBuilder = app(StudyImageAttachmentBuilder::class);
        foreach ($this->imagenes as $imagen) {
            $attachment = $imageAttachmentBuilder->make($imagen);

            if (! $attachment) {
                continue;
            }

            $mail->attachData($attachment['data'], $attachment['name'], [
                'mime' => $attachment['mime'],
            ]);
        }

        $pdfGenerator = app(ReportPdfGenerator::class);

        foreach ($this->reportes as $reporte) {
            $pdf = $pdfGenerator->make($reporte);
            $mail->attachData($pdf['data'], $pdf['name'], [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
