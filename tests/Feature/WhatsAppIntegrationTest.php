<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_messages_dashboard_lists_real_patients_with_phone_numbers(): void
    {
        $user = User::create([
            'name' => 'Usuario del panel',
            'email' => 'whatsapp-dashboard@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        Paciente::create([
            'folio' => 'P-WA-000',
            'nombre_completo' => 'Paciente Real',
            'telefono' => '+52 55 1111 2222',
        ]);

        $this->actingAs($user)
            ->get(route('mensajes'))
            ->assertOk()
            ->assertSeeText('Paciente Real')
            ->assertDontSeeText('Maria Gonzalez');
    }

    public function test_authenticated_user_can_send_a_whatsapp_message(): void
    {
        config([
            'services.whatsapp.access_token' => 'test-token',
            'services.whatsapp.phone_number_id' => '123456789',
            'services.whatsapp.api_version' => 'v21.0',
        ]);

        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messages' => [['id' => 'wamid.test-123']],
            ]),
        ]);

        $user = User::create([
            'name' => 'Usuario de prueba',
            'email' => 'whatsapp-test@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $patient = Paciente::create([
            'folio' => 'P-WA-001',
            'nombre_completo' => 'Paciente WhatsApp',
            'telefono' => '+52 55 1234 5678',
        ]);
        WhatsAppMessage::create([
            'paciente_id' => $patient->id,
            'wa_id' => '525512345678',
            'direction' => 'inbound',
            'type' => 'text',
            'body' => 'Hola',
            'status' => 'received',
            'sent_at' => now()->subMinutes(5),
        ]);

        $this->actingAs($user)
            ->postJson(route('mensajes.whatsapp.send'), [
                'paciente_id' => $patient->id,
                'message' => 'Mensaje de prueba',
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'accepted');

        $this->assertDatabaseHas('whatsapp_messages', [
            'paciente_id' => $patient->id,
            'meta_message_id' => 'wamid.test-123',
            'wa_id' => '525512345678',
            'direction' => 'outbound',
            'body' => 'Mensaje de prueba',
            'status' => 'accepted',
        ]);

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://graph.facebook.com/v21.0/123456789/messages'
                && $request['messaging_product'] === 'whatsapp'
                && $request['to'] === '525512345678'
                && $request['text']['body'] === 'Mensaje de prueba';
        });
    }

    public function test_text_messages_require_an_open_whatsapp_customer_service_window(): void
    {
        config([
            'services.whatsapp.access_token' => 'test-token',
            'services.whatsapp.phone_number_id' => '123456789',
            'services.whatsapp.api_version' => 'v21.0',
        ]);

        Http::fake();

        $user = User::create([
            'name' => 'Usuario sin ventana',
            'email' => 'whatsapp-window@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $patient = Paciente::create([
            'folio' => 'P-WA-004',
            'nombre_completo' => 'Paciente Sin Ventana',
            'telefono' => '+52 55 9999 8888',
        ]);

        $this->actingAs($user)
            ->postJson(route('mensajes.whatsapp.send'), [
                'paciente_id' => $patient->id,
                'message' => 'Hola',
            ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'WhatsApp no permite iniciar una conversacion con texto libre. El paciente debe escribir primero o debes enviar una plantilla aprobada de WhatsApp.');

        $this->assertSame(0, WhatsAppMessage::where('direction', 'outbound')->count());
        Http::assertNothingSent();
    }

    public function test_meta_send_errors_are_saved_and_returned_with_details(): void
    {
        config([
            'services.whatsapp.access_token' => 'test-token',
            'services.whatsapp.phone_number_id' => '123456789',
            'services.whatsapp.api_version' => 'v21.0',
        ]);

        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Message failed to send because more than 24 hours have passed.',
                    'code' => 131047,
                    'error_data' => [
                        'details' => 'Use an approved template message to re-engage the user.',
                    ],
                ],
            ], 400),
        ]);

        $user = User::create([
            'name' => 'Usuario Error Meta',
            'email' => 'whatsapp-meta-error@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $patient = Paciente::create([
            'folio' => 'P-WA-005',
            'nombre_completo' => 'Paciente Error Meta',
            'telefono' => '+52 55 2222 9999',
        ]);
        WhatsAppMessage::create([
            'paciente_id' => $patient->id,
            'wa_id' => '525522229999',
            'direction' => 'inbound',
            'type' => 'text',
            'body' => 'Hola',
            'status' => 'received',
            'sent_at' => now()->subMinutes(5),
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('mensajes.whatsapp.send'), [
                'paciente_id' => $patient->id,
                'message' => 'Seguimos en contacto',
            ])
            ->assertStatus(502)
            ->json('message');

        $this->assertStringContainsString('Message failed to send', $response);
        $this->assertStringContainsString('Codigo Meta: 131047', $response);
        $this->assertDatabaseHas('whatsapp_messages', [
            'paciente_id' => $patient->id,
            'direction' => 'outbound',
            'status' => 'failed',
            'body' => 'Seguimos en contacto',
        ]);
    }

    public function test_webhook_verification_returns_the_meta_challenge(): void
    {
        config(['services.whatsapp.webhook_verify_token' => 'verify-me']);

        $this->get('/webhooks/whatsapp?hub_mode=subscribe&hub_verify_token=verify-me&hub_challenge=challenge-123')
            ->assertOk()
            ->assertSeeText('challenge-123');
    }

    public function test_signed_webhook_stores_an_incoming_message(): void
    {
        config(['services.whatsapp.app_secret' => 'app-secret']);

        $user = User::create([
            'name' => 'Usuario Webhook',
            'email' => 'webhook@example.com',
            'password' => 'password',
        ]);
        $patient = Paciente::create([
            'folio' => 'P-WA-002',
            'nombre_completo' => 'Paciente Entrante',
            'telefono' => '+52 55 8765 4321',
        ]);

        $payload = [
            'entry' => [[
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'metadata' => ['phone_number_id' => '123456789'],
                        'messages' => [[
                            'id' => 'wamid.incoming-123',
                            'from' => '525587654321',
                            'timestamp' => '1782576000',
                            'type' => 'text',
                            'text' => ['body' => 'Hola, tengo una pregunta.'],
                        ]],
                    ],
                ]],
            ]],
        ];
        $json = json_encode($payload, JSON_THROW_ON_ERROR);
        $signature = 'sha256='.hash_hmac('sha256', $json, 'app-secret');

        $this->call(
            'POST',
            '/webhooks/whatsapp',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_HUB_SIGNATURE_256' => $signature,
            ],
            $json,
        )->assertOk();

        $this->assertDatabaseHas('whatsapp_messages', [
            'paciente_id' => $patient->id,
            'meta_message_id' => 'wamid.incoming-123',
            'wa_id' => '525587654321',
            'direction' => 'inbound',
            'body' => 'Hola, tengo una pregunta.',
            'status' => 'received',
        ]);
    }

    public function test_webhook_rejects_an_invalid_signature(): void
    {
        config(['services.whatsapp.app_secret' => 'app-secret']);

        $this->withHeader('X-Hub-Signature-256', 'sha256=invalid')
            ->postJson('/webhooks/whatsapp', ['entry' => []])
            ->assertForbidden();

        $this->assertSame(0, WhatsAppMessage::count());
    }

    public function test_greeting_receives_only_one_automatic_reply_during_webhook_retries(): void
    {
        config([
            'services.whatsapp.app_secret' => 'app-secret',
            'services.whatsapp.access_token' => 'test-token',
            'services.whatsapp.phone_number_id' => '123456789',
            'services.whatsapp.api_version' => 'v21.0',
            'services.whatsapp.auto_reply_enabled' => true,
            'services.whatsapp.auto_reply_message' => 'Hola, somos ENCLAII. ¿Cómo podemos ayudarte?',
            'services.whatsapp.auto_reply_cooldown_hours' => 24,
        ]);

        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messages' => [['id' => 'wamid.auto-reply-123']],
            ]),
        ]);

        $user = User::create([
            'name' => 'Usuario Chatbot',
            'email' => 'chatbot@example.com',
            'password' => 'password',
        ]);
        $patient = Paciente::create([
            'folio' => 'P-WA-003',
            'nombre_completo' => 'Paciente Chatbot',
            'telefono' => '+52 55 2222 3333',
        ]);

        $payload = [
            'entry' => [[
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'metadata' => ['phone_number_id' => '123456789'],
                        'messages' => [[
                            'id' => 'wamid.greeting-123',
                            'from' => '525522223333',
                            'timestamp' => '1782576000',
                            'type' => 'text',
                            'text' => ['body' => 'Hola'],
                        ]],
                    ],
                ]],
            ]],
        ];
        $json = json_encode($payload, JSON_THROW_ON_ERROR);
        $signature = 'sha256='.hash_hmac('sha256', $json, 'app-secret');
        $server = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_HUB_SIGNATURE_256' => $signature,
        ];

        $this->call('POST', '/webhooks/whatsapp', [], [], [], $server, $json)->assertOk();
        $this->call('POST', '/webhooks/whatsapp', [], [], [], $server, $json)->assertOk();

        $this->assertDatabaseHas('whatsapp_messages', [
            'paciente_id' => $patient->id,
            'meta_message_id' => 'wamid.greeting-123',
            'direction' => 'inbound',
            'body' => 'Hola',
        ]);
        $this->assertDatabaseHas('whatsapp_messages', [
            'paciente_id' => $patient->id,
            'meta_message_id' => 'wamid.auto-reply-123',
            'direction' => 'outbound',
            'type' => 'auto_reply',
            'body' => 'Hola, somos ENCLAII. ¿Cómo podemos ayudarte?',
        ]);
        $this->assertSame(2, WhatsAppMessage::count());
        Http::assertSentCount(1);
    }
}
