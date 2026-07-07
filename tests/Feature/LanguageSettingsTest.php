<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_configuration_uses_only_the_internal_language_selector(): void
    {
        $user = User::create([
            'name' => 'Doctor Idioma',
            'email' => 'idioma@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('configuracion'))
            ->assertOk()
            ->assertSee('<html lang="es" class="notranslate" translate="no">', false)
            ->assertSee('<meta name="google" content="notranslate">', false)
            ->assertSee('window.enclaiiSetLanguage', false)
            ->assertSee('onchange="window.enclaiiSetLanguage(this.value)"', false);
    }
}
