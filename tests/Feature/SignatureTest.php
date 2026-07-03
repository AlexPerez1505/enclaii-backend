<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SignatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_and_view_a_private_signature(): void
    {
        Storage::fake('local');
        $user = $this->user();

        $this->actingAs($user)
            ->postJson(route('configuracion.signature.store'), [
                'signature' => $this->signatureImage('firma.png'),
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $user->refresh();

        $this->assertNotNull($user->signature_path);
        $this->assertNotNull($user->signature_updated_at);
        Storage::disk('local')->assertExists($user->signature_path);

        $this->actingAs($user)
            ->get(route('configuracion.signature.show'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, no-store, private');
    }

    public function test_updating_signature_removes_the_previous_private_file(): void
    {
        Storage::fake('local');
        $user = $this->user();

        $this->actingAs($user)->postJson(route('configuracion.signature.store'), [
            'signature' => $this->signatureImage('primera.png'),
        ])->assertOk();

        $firstPath = $user->fresh()->signature_path;

        $this->actingAs($user)->postJson(route('configuracion.signature.store'), [
            'signature' => $this->signatureImage('segunda.png'),
        ])->assertOk();

        $secondPath = $user->fresh()->signature_path;

        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('local')->assertMissing($firstPath);
        Storage::disk('local')->assertExists($secondPath);
    }

    public function test_user_can_delete_their_signature(): void
    {
        Storage::fake('local');
        $user = $this->user();

        $this->actingAs($user)->postJson(route('configuracion.signature.store'), [
            'signature' => $this->signatureImage('firma.png'),
        ])->assertOk();

        $path = $user->fresh()->signature_path;

        $this->actingAs($user)
            ->deleteJson(route('configuracion.signature.destroy'))
            ->assertOk()
            ->assertJsonPath('ok', true);

        $user->refresh();

        $this->assertNull($user->signature_path);
        $this->assertNull($user->signature_updated_at);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_signature_upload_rejects_non_image_files(): void
    {
        Storage::fake('local');
        $user = $this->user();

        $this->actingAs($user)
            ->postJson(route('configuracion.signature.store'), [
                'signature' => UploadedFile::fake()->create('firma.svg', 10, 'image/svg+xml'),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('signature');

        $this->assertNull($user->fresh()->signature_path);
    }

    private function user(): User
    {
        return User::create([
            'name' => 'Doctor Firma',
            'email' => 'firma'.uniqid().'@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
    }

    private function signatureImage(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'signature-test-');
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        );
        file_put_contents($path, $png);

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
