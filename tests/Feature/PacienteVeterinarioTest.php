<?php

namespace Tests\Feature;

use App\Models\Clinica;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifica los campos veterinarios nuevos de Paciente (especie, raza, tutor,
 * microchip, esterilizado) y su correcto guardado desde el formulario.
 */
class PacienteVeterinarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_guarda_los_campos_veterinarios(): void
    {
        $user = $this->vetUser();

        $this->actingAs($user)->post(route('pacientes.store'), [
            'folio' => 'P-001',
            'nombre_completo' => 'Firulais',
            'especie' => 'Perro',
            'raza' => 'Labrador',
            'esterilizado' => '1',
            'color_pelaje' => 'Dorado',
            'microchip' => '985141000123456',
            'nombre_tutor' => 'Juan Pérez',
        ]);

        $paciente = Paciente::where('folio', 'P-001')->firstOrFail();
        $this->assertSame('Perro', $paciente->especie);
        $this->assertSame('Labrador', $paciente->raza);
        $this->assertTrue($paciente->esterilizado);
        $this->assertSame('Dorado', $paciente->color_pelaje);
        $this->assertSame('985141000123456', $paciente->microchip);
        $this->assertSame('Juan Pérez', $paciente->nombre_tutor);
    }

    public function test_update_permite_desmarcar_esterilizado(): void
    {
        $user = $this->vetUser();

        $paciente = Paciente::create([
            'clinica_id' => $user->clinica_id,
            'folio' => 'P-002',
            'nombre_completo' => 'Michi',
            'especie' => 'Gato',
            'esterilizado' => true,
        ]);

        $this->assertTrue($paciente->esterilizado);

        // Actualizar pacientes es una acción "crítica" que por defecto pide
        // reconfirmar contraseña; se desactiva para poder probar el guardado.
        $user->securitySetting()->create(['require_password_for_patients' => false]);

        // El checkbox no envía nada cuando se desmarca: no debe quedarse
        // pegado en "true" (bug corregido en PacienteController::update()).
        $this->actingAs($user)->put(route('pacientes.update', $paciente), [
            'folio' => 'P-002',
            'nombre_completo' => 'Michi',
            'especie' => 'Gato',
            // 'esterilizado' deliberadamente omitido, como lo haría el navegador.
        ])->assertRedirect(route('pacientes.index'));

        $this->assertFalse($paciente->refresh()->esterilizado);
    }

    private function vetUser(): User
    {
        $clinica = Clinica::create(['nombre' => 'Vet Test', 'vertical' => 'veterinaria', 'is_shared' => false]);

        return User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'vertical' => 'veterinaria',
            'name' => 'Vet Doctor',
            'email' => 'vetdoc'.uniqid().'@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);
    }
}
