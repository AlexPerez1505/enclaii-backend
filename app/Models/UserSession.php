<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    protected $table = 'sessions';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    protected function casts(): array
    {
        return [
            'last_activity' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastActivityAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->last_activity);
    }

    public function deviceLabel(): string
    {
        $agent = $this->user_agent ?? '';
        $platform = match (true) {
            str_contains($agent, 'Windows') => 'Windows',
            str_contains($agent, 'Macintosh') => 'macOS',
            str_contains($agent, 'iPhone'), str_contains($agent, 'iPad') => 'iOS',
            str_contains($agent, 'Android') => 'Android',
            str_contains($agent, 'Linux') => 'Linux',
            default => 'Dispositivo desconocido',
        };
        $browser = match (true) {
            str_contains($agent, 'Edg/') => 'Edge',
            str_contains($agent, 'Chrome/') => 'Chrome',
            str_contains($agent, 'Firefox/') => 'Firefox',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Navegador desconocido',
        };

        return $platform.' · '.$browser;
    }

    public function locationLabel(): string
    {
        $ip = $this->ip_address;

        if (! $ip) {
            return 'Ubicación no disponible';
        }

        if ($ip === '127.0.0.1' || $ip === '::1' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false) {
            return 'Red local';
        }

        return 'Ubicación no disponible';
    }
}
