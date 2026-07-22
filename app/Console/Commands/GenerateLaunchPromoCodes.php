<?php

namespace App\Console\Commands;

use App\Models\LaunchPromoCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateLaunchPromoCodes extends Command
{
    protected $signature = 'promo:generate-launch-codes
        {--count=32 : Total de codigos a generar}
        {--test=2 : Codigos marcados para pruebas}
        {--plan=clinica : Plan que activara Stripe}
        {--interval=month : Intervalo del precio de Stripe}
        {--months=6 : Meses gratis del trial}
        {--expires= : Fecha de expiracion opcional, formato YYYY-MM-DD}
        {--export : Guarda CSV y SVG de cada QR en storage/app/private}';

    protected $description = 'Genera codigos QR promocionales de lanzamiento con trial de Stripe.';

    public function handle(): int
    {
        $count = max(1, (int) $this->option('count'));
        $testCount = min($count, max(0, (int) $this->option('test')));
        $launchCount = $count - $testCount;
        $trialMonths = max(1, (int) $this->option('months'));
        $expiresAt = $this->parseExpiresAt($this->option('expires'));

        $created = [];

        for ($i = 0; $i < $launchCount; $i++) {
            $created[] = $this->createCode(LaunchPromoCode::TYPE_LAUNCH, $trialMonths, $expiresAt);
        }

        for ($i = 0; $i < $testCount; $i++) {
            $created[] = $this->createCode(LaunchPromoCode::TYPE_TEST, $trialMonths, $expiresAt);
        }

        $rows = collect($created)->map(function (array $item): array {
            return [
                'Codigo' => $item['promo']->code,
                'Tipo' => $item['promo']->type,
                'Plan' => $item['promo']->plan,
                'Trial' => $item['promo']->trial_months.' meses',
                'URL' => $item['url'],
            ];
        })->all();

        $this->table(['Codigo', 'Tipo', 'Plan', 'Trial', 'URL'], $rows);

        if ($this->option('export')) {
            $dir = 'promo-codes/'.now()->format('Ymd-His');
            $this->exportFiles($dir, $created);
            $this->info('Archivos guardados en: '.storage_path('app/private/'.$dir));
        }

        $this->info("Listo: {$count} codigos generados ({$launchCount} lanzamiento, {$testCount} prueba).");

        return self::SUCCESS;
    }

    private function createCode(string $type, int $trialMonths, ?Carbon $expiresAt): array
    {
        $token = Str::random(64);
        $code = $this->nextCode($type);

        $promo = LaunchPromoCode::create([
            'code' => $code,
            'token' => $token,
            'token_hash' => hash('sha256', $token),
            'type' => $type,
            'plan' => (string) $this->option('plan'),
            'interval' => (string) $this->option('interval'),
            'trial_months' => $trialMonths,
            'status' => LaunchPromoCode::STATUS_ACTIVE,
            'expires_at' => $expiresAt,
        ]);

        return [
            'promo' => $promo,
            'token' => $token,
            'url' => route('promo.register.show', ['token' => $token]),
            'qr_url' => route('promo.register.qr', ['token' => $token]),
        ];
    }

    private function nextCode(string $type): string
    {
        $prefix = $type === LaunchPromoCode::TYPE_TEST
            ? 'ENCLAII-TEST'
            : 'ENCLAII-LAUNCH';
        $next = LaunchPromoCode::query()->where('type', $type)->count() + 1;

        do {
            $code = $prefix.'-'.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
            $next++;
        } while (LaunchPromoCode::query()->where('code', $code)->exists());

        return $code;
    }

    private function parseExpiresAt(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d', $value)->endOfDay();
    }

    private function exportFiles(string $dir, array $created): void
    {
        Storage::disk('local')->makeDirectory($dir);

        $csvRows = ["codigo,tipo,plan,intervalo,trial_meses,url,qr_url"];

        foreach ($created as $item) {
            $promo = $item['promo'];
            Storage::disk('local')->put(
                $dir.'/'.$promo->code.'.svg',
                $this->qrSvg($item['url']),
            );

            $csvRows[] = implode(',', [
                $promo->code,
                $promo->type,
                $promo->plan,
                $promo->interval,
                $promo->trial_months,
                $item['url'],
                $item['qr_url'],
            ]);
        }

        Storage::disk('local')->put($dir.'/codigos.csv', implode(PHP_EOL, $csvRows).PHP_EOL);
    }

    private function qrSvg(string $url): string
    {
        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 420,
            margin: 18,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(6, 16, 50),
            backgroundColor: new Color(255, 255, 255),
        );

        return (new SvgWriter)->write($qrCode)->getString();
    }
}
