<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SessionLimitService
{
    private const PLAN_SESSION_LIMITS = [
        'clinica' => 2,
        'hospital' => 3,
        'red_medica' => 5,
    ];

    public function limitFor(User $user): int
    {
        $plan = str_replace('-', '_', $user->billingUser()->stripe_plan ?: 'clinica');

        return self::PLAN_SESSION_LIMITS[$plan] ?? 1;
    }

    public function syncCurrentDatabaseSession(Request $request, User $user): void
    {
        if (! $this->databaseSessionsAvailable()) {
            return;
        }

        $table = $this->sessionTable();
        $sessionId = $request->session()->getId();
        $attributes = [
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_activity' => now()->timestamp,
        ];

        $updated = DB::table($table)
            ->where('id', $sessionId)
            ->update($attributes);

        if ($updated === 0) {
            DB::table($table)->insert([
                'id' => $sessionId,
                ...$attributes,
                'payload' => '',
            ]);
        }
    }

    public function enforceDatabaseSessions(User $user, string $currentSessionId): int
    {
        if (! $this->databaseSessionsAvailable()) {
            return 0;
        }

        $limit = max(1, $this->limitFor($user));
        $cutoff = now()->subMinutes((int) config('session.lifetime'))->timestamp;

        UserSession::query()
            ->where('user_id', $user->id)
            ->where('last_activity', '<', $cutoff)
            ->delete();

        $sessions = UserSession::query()
            ->where('user_id', $user->id)
            ->where('last_activity', '>=', $cutoff)
            ->orderByDesc('last_activity')
            ->get(['id', 'last_activity']);

        if ($sessions->count() <= $limit) {
            return 0;
        }

        $keepIds = $this->keptSessionIds($sessions, $currentSessionId, $limit);

        return UserSession::query()
            ->where('user_id', $user->id)
            ->whereNotIn('id', $keepIds)
            ->delete();
    }

    public function enforceApiTokens(User $user, int|string|null $currentTokenId, string $tokenName = 'tauri-app'): int
    {
        if (! method_exists($user, 'tokens')) {
            return 0;
        }

        $limit = max(1, $this->limitFor($user));
        $tokens = $user->tokens()
            ->where('name', $tokenName)
            ->orderByDesc(DB::raw('COALESCE(last_used_at, created_at)'))
            ->orderByDesc('id')
            ->get(['id']);

        if ($tokens->count() <= $limit) {
            return 0;
        }

        $keepIds = collect([$currentTokenId])
            ->filter()
            ->merge($tokens->where('id', '!=', $currentTokenId)->take($limit - 1)->pluck('id'))
            ->unique()
            ->values();

        return $user->tokens()
            ->where('name', $tokenName)
            ->whereNotIn('id', $keepIds)
            ->delete();
    }

    private function keptSessionIds(Collection $sessions, string $currentSessionId, int $limit): Collection
    {
        return collect([$currentSessionId])
            ->merge($sessions->where('id', '!=', $currentSessionId)->take($limit - 1)->pluck('id'))
            ->filter()
            ->unique()
            ->values();
    }

    private function databaseSessionsAvailable(): bool
    {
        return config('session.driver') === 'database' && Schema::hasTable($this->sessionTable());
    }

    private function sessionTable(): string
    {
        return (string) config('session.table', 'sessions');
    }
}
