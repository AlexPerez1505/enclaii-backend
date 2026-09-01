<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class MailSenderResolver
{
    public function address(): ?string
    {
        $configuredFrom = $this->clean(config('mail.from.address'));
        $smtpUsername = $this->clean(config('mail.mailers.smtp.username'));

        if ($this->isGmailSmtp() && $this->isValidEmail($smtpUsername)) {
            return $smtpUsername;
        }

        if ($this->isUsableFromAddress($configuredFrom)) {
            return $configuredFrom;
        }

        if ($this->isValidEmail($smtpUsername)) {
            return $smtpUsername;
        }

        return null;
    }

    public function name(): string
    {
        $name = $this->clean(config('mail.from.name'));

        return $name !== '' ? $name : 'ENCLAII';
    }

    public function applyToConfig(): ?string
    {
        $address = $this->address();

        if ($address) {
            config([
                'mail.from.address' => $address,
                'mail.from.name' => $this->name(),
            ]);
        }

        return $address;
    }

    public function configurationMessage(): string
    {
        return 'Configura MAIL_FROM_ADDRESS o MAIL_USERNAME con el Gmail autorizado para enviar correos reales.';
    }

    public function deliveryFailureMessage(Throwable $exception): string
    {
        if ($exception instanceof TransportExceptionInterface) {
            return 'Gmail rechazo el envio. Revisa MAIL_USERNAME, MAIL_PASSWORD y que el remitente sea el Gmail autorizado.';
        }

        return 'No se pudo enviar el correo real en este momento.';
    }

    private function clean(mixed $value): string
    {
        $value = trim((string) $value, " \t\n\r\0\x0B\"'");

        return Str::lower($value) === 'null' ? '' : $value;
    }

    private function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function isUsableFromAddress(string $email): bool
    {
        return $this->isValidEmail($email) && ! $this->isPlaceholderAddress($email);
    }

    private function isPlaceholderAddress(string $email): bool
    {
        $email = Str::lower($email);

        return in_array($email, [
            'hello@example.com',
            'noreply@example.com',
            'example@example.com',
        ], true) || Str::endsWith($email, [
            '@example.com',
            '@example.test',
            '@localhost',
        ]);
    }

    private function isGmailSmtp(): bool
    {
        $host = Str::lower($this->clean(config('mail.mailers.smtp.host')));

        return Str::contains($host, 'gmail');
    }
}
