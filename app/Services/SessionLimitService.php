<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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


    public function checkInactivity(Request $request, User $user)
    {
        $timeout = (int) ($user->resolvedSettings()['session_timeout'] ?? 30);

        if ($timeout <= 0) {
            return null;
        }

        $sessionId = $request->session()->getId();
        $session = UserSession::find($sessionId);

        if ($session && $session->last_activity < now()->subMinutes($timeout)->timestamp) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $request->expectsJson()
                ? response()->json(['message' => 'Sesión cerrada por inactividad.'], 401)
                : redirect('/login')->with('status', 'Sesión cerrada por inactividad.');
        }

        return null;
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

        if (DB::table($table)->where('id', $sessionId)->exists()) {
            DB::table($table)->where('id', $sessionId)->update($attributes);

            return;
        }

        try {
            DB::table($table)->insert([
                'id' => $sessionId,
                ...$attributes,
                'payload' => '',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Condición de carrera: otra petición concurrente ya insertó la fila
            // (p. ej. StartSession) entre nuestro exists() y el insert().
            if ($this->isDuplicateKeyError($e)) {
                DB::table($table)->where('id', $sessionId)->update($attributes);

                return;
            }

            throw $e;
        }
    }

    private function isDuplicateKeyError(\Illuminate\Database\QueryException $e): bool
    {
        return (int) ($e->errorInfo[1] ?? 0) === 1062;
    }

    public function enforceDatabaseSessions(User $user, string $currentSessionId): int
    {
        if (! $this->databaseSessionsAvailable()) {
            return 0;
        }

        $limit = max(1, $this->limitFor($user));
        $timeout = (int) ($user->resolvedSettings()['session_timeout'] ?? config('session.lifetime'));
        $timeout = $timeout > 0 ? $timeout : (int) config('session.lifetime');
        $cutoff = now()->subMinutes($timeout)->timestamp;

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
