<?php

namespace Tests\Feature;

use App\Http\Controllers\UserSessionController;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Tests\TestCase;

class UserSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_page_lists_real_connected_sessions(): void
    {
        $user = $this->user();
        $this->actingAs($user)->withSession(['session_test' => true]);
        $currentSessionId = session()->getId();

        $this->createSession($currentSessionId, $user, '192.168.1.10', 'Mozilla/5.0 (Windows NT 10.0) Chrome/124.0');
        $this->createSession('other-session', $user, '203.0.113.8', 'Mozilla/5.0 (iPhone) Safari/605.1');

        $this->get(route('configuracion', ['tab' => 'seguridad']))
            ->assertOk()
            ->assertSee('Windows · Chrome')
            ->assertSee('iOS · Safari')
            ->assertSee('Este dispositivo')
            ->assertSee('Red local');
    }

    public function test_user_can_close_one_of_their_other_sessions(): void
    {
        $user = $this->user();
        $other = $this->createSession('other-session', $user);

        $this->actingAs($user)
            ->deleteJson(route('configuracion.sessions.destroy', $other->id))
            ->assertOk()
            ->assertJsonPath('ok', true);

        $this->assertDatabaseMissing('sessions', ['id' => $other->id]);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'session_revoked',
        ]);
    }

    public function test_user_cannot_close_current_or_another_users_session(): void
    {
        $user = $this->user();
        $otherUser = $this->user('other');
        $foreignSession = $this->createSession('foreign-session', $otherUser);

        $currentSessionId = str_repeat('c', 40);
        $request = $this->sessionRequest($user, $currentSessionId);
        $response = app(UserSessionController::class)->destroy($request, $currentSessionId);
        $this->assertSame(422, $response->status());

        $this->actingAs($user)
            ->deleteJson(route('configuracion.sessions.destroy', $foreignSession->id))
            ->assertNotFound();

        $this->assertDatabaseHas('sessions', ['id' => $foreignSession->id]);
    }

    public function test_user_can_close_all_other_sessions_without_affecting_current_session(): void
    {
        $user = $this->user();
        $otherUser = $this->user('other');

        $currentSessionId = str_repeat('c', 40);
        $this->createSession($currentSessionId, $user);
        $this->createSession('other-one', $user);
        $this->createSession('other-two', $user);
        $this->createSession('foreign-session', $otherUser);

        $request = $this->sessionRequest($user, $currentSessionId);
        $response = app(UserSessionController::class)->destroyOthers($request);

        $this->assertSame(200, $response->status());
        $this->assertSame(2, $response->getData(true)['closed_sessions']);

        $this->assertDatabaseHas('sessions', ['id' => $currentSessionId]);
        $this->assertDatabaseMissing('sessions', ['id' => 'other-one']);
        $this->assertDatabaseMissing('sessions', ['id' => 'other-two']);
        $this->assertDatabaseHas('sessions', ['id' => 'foreign-session']);
        $log = ActivityLog::query()->where('action', 'other_sessions_revoked')->firstOrFail();
        $this->assertSame(2, $log->metadata['closed_sessions']);
    }

    private function user(string $suffix = ''): User
    {
        return User::create([
            'name' => 'Doctor Sesiones',
            'email' => 'sesiones'.$suffix.uniqid().'@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);
    }

    private function createSession(
        string $id,
        User $user,
        string $ip = '192.168.1.20',
        string $agent = 'Mozilla/5.0 (Windows NT 10.0) Chrome/124.0',
    ): UserSession {
        return UserSession::create([
            'id' => $id,
            'user_id' => $user->id,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'payload' => base64_encode('session'),
            'last_activity' => now()->timestamp,
        ]);
    }

    private function sessionRequest(User $user, string $sessionId): Request
    {
        $request = Request::create('/configuracion/seguridad/sesiones', 'DELETE');
        $request->setUserResolver(fn () => $user);

        $session = new Store('test', new ArraySessionHandler(120));
        $session->start();
        $session->setId($sessionId);
        $request->setLaravelSession($session);

        return $request;
    }
}
