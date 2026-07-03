<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrintTestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_open_the_default_print_calibration_sheet(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get(route('configuracion.print-test'))
            ->assertOk()
            ->assertSee('PRUEBA DE IMPRESIÓN Y CALIBRACIÓN')
            ->assertSee('DOCUMENTO DE PRUEBA — SIN VALIDEZ CLÍNICA')
            ->assertSee('Carta')
            ->assertSee('Vertical')
            ->assertSee('PACIENTE DE PRUEBA');
    }

    public function test_print_sheet_respects_page_orientation_and_visibility_options(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get(route('configuracion.print-test', [
                'page_size' => 'a4',
                'orientation' => 'landscape',
                'show_header' => '0',
                'show_logo' => '0',
                'show_signature' => '0',
                'use_color' => '0',
                'mode' => 'preview',
            ]))
            ->assertOk()
            ->assertSee('A4')
            ->assertSee('Horizontal')
            ->assertSee('filter:grayscale(1)', false)
            ->assertDontSee('Hoja de calibración para reportes médicos')
            ->assertDontSee('Espacio reservado para firma digital');
    }

    public function test_print_sheet_embeds_the_authenticated_users_private_signature(): void
    {
        Storage::fake('local');
        $user = $this->user();
        $path = 'signatures/'.$user->id.'/firma.png';
        Storage::disk('local')->put($path, $this->png());
        $user->forceFill([
            'signature_path' => $path,
            'signature_updated_at' => now(),
        ])->save();

        $this->actingAs($user)
            ->get(route('configuracion.print-test', ['show_signature' => '1']))
            ->assertOk()
            ->assertSee('data:image/png;base64,', false)
            ->assertSee('Firma digital configurada');
    }

    public function test_print_sheet_rejects_unknown_formats(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->getJson(route('configuracion.print-test', [
                'page_size' => 'tabloid',
                'orientation' => 'diagonal',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['page_size', 'orientation']);
    }

    private function user(): User
    {
        return User::create([
            'name' => 'Doctor Impresión',
            'email' => 'impresion'.uniqid().'@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
    }

    private function png(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        );
    }
}
