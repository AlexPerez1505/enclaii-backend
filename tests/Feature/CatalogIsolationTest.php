<?php

namespace Tests\Feature;

use App\Models\Anestesiologo;
use App\Models\Clinica;
use App\Models\Hallazgo;
use App\Models\Medico;
use App\Models\Plantilla;
use App\Models\Procedimiento;
use App\Models\Sala;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifica el aislamiento multi-tenant de los catálogos clínicos (Sala, Medico,
 * Anestesiologo, Procedimiento, Hallazgo) y el comportamiento de "copia sobre
 * escritura" de las plantillas de reporte (Plantilla).
 */
class CatalogIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_salas_are_isolated_and_cannot_be_edited_or_deleted_cross_tenant(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'sala-a@example.com');
        [$clinicaB, $userB] = $this->clinicaConUsuario('Clínica B', 'sala-b@example.com');

        $salaA = Sala::create(['clinica_id' => $clinicaA->id, 'nombre' => 'Sala A', 'activa' => true]);
        Sala::create(['clinica_id' => $clinicaB->id, 'nombre' => 'Sala B', 'activa' => true]);

        $this->actingAs($userA);
        $this->assertSame(1, Sala::count());
        $this->assertSame('Sala A', Sala::first()->nombre);

        $salaB = Sala::withoutGlobalScopes()->where('clinica_id', $clinicaB->id)->first();

        $this->putJson(route('salas.update', $salaB), ['nombre' => 'Hackeada', 'activa' => true])
            ->assertNotFound();
        $this->deleteJson(route('salas.destroy', $salaB))->assertNotFound();

        $this->assertDatabaseHas('salas', ['id' => $salaB->id, 'nombre' => 'Sala B']);

        // El propietario sí puede modificar la suya.
        $this->putJson(route('salas.update', $salaA), ['nombre' => 'Sala A editada', 'activa' => true])
            ->assertOk();
    }

    public function test_medicos_are_isolated_and_cannot_be_edited_or_deleted_cross_tenant(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'medico-a@example.com');
        [$clinicaB] = $this->clinicaConUsuario('Clínica B', 'medico-b@example.com');

        Medico::create(['clinica_id' => $clinicaA->id, 'nombres' => 'Ana']);
        $medicoB = Medico::create(['clinica_id' => $clinicaB->id, 'nombres' => 'Beto']);

        $this->actingAs($userA);
        $this->assertSame(1, Medico::count());

        $this->putJson(route('medicos.update', $medicoB), ['nombres' => 'Hackeado'])->assertNotFound();
        $this->deleteJson(route('medicos.destroy', $medicoB))->assertNotFound();
        $this->assertDatabaseHas('medicos', ['id' => $medicoB->id, 'nombres' => 'Beto']);
    }

    public function test_anestesiologos_are_isolated_and_cannot_be_edited_or_deleted_cross_tenant(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'anest-a@example.com');
        [$clinicaB] = $this->clinicaConUsuario('Clínica B', 'anest-b@example.com');

        Anestesiologo::create(['clinica_id' => $clinicaA->id, 'nombres' => 'Ana']);
        $anestB = Anestesiologo::create(['clinica_id' => $clinicaB->id, 'nombres' => 'Beto']);

        $this->actingAs($userA);
        $this->assertSame(1, Anestesiologo::count());

        $this->putJson(route('anestesiologos.update', $anestB), ['nombres' => 'Hackeado'])->assertNotFound();
        $this->deleteJson(route('anestesiologos.destroy', $anestB))->assertNotFound();
        $this->assertDatabaseHas('anestesiologos', ['id' => $anestB->id, 'nombres' => 'Beto']);
    }

    public function test_procedimientos_are_isolated_per_clinic(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'proc-a@example.com');
        [$clinicaB] = $this->clinicaConUsuario('Clínica B', 'proc-b@example.com');

        Procedimiento::create(['clinica_id' => $clinicaA->id, 'nombre' => 'Colonoscopia']);
        $procB = Procedimiento::create(['clinica_id' => $clinicaB->id, 'nombre' => 'Rinoscopia']);

        $this->actingAs($userA);
        $this->assertSame(['Colonoscopia'], Procedimiento::pluck('nombre')->all());

        // Dos clínicas pueden tener un procedimiento con el mismo nombre sin colisionar.
        Procedimiento::create(['clinica_id' => $clinicaA->id, 'nombre' => 'Rinoscopia']);
        $this->assertSame(2, Procedimiento::count());

        $this->putJson(route('procedimientos.update', $procB), ['nombre' => 'Hackeado'])->assertNotFound();
        $this->deleteJson(route('procedimientos.destroy', $procB))->assertNotFound();
        $this->assertDatabaseHas('procedimientos', ['id' => $procB->id, 'nombre' => 'Rinoscopia']);
    }

    public function test_hallazgos_are_isolated_per_clinic(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'hall-a@example.com');
        [$clinicaB] = $this->clinicaConUsuario('Clínica B', 'hall-b@example.com');

        Hallazgo::create(['clinica_id' => $clinicaA->id, 'nombre' => 'Pólipo']);
        Hallazgo::create(['clinica_id' => $clinicaB->id, 'nombre' => 'Pólipo']);

        $this->actingAs($userA);
        $this->assertSame(1, Hallazgo::count());
    }

    public function test_catalog_routes_require_authentication(): void
    {
        [$clinicaA] = $this->clinicaConUsuario('Clínica A', 'guest-a@example.com');
        $sala = Sala::create(['clinica_id' => $clinicaA->id, 'nombre' => 'Sala', 'activa' => true]);

        $this->putJson(route('salas.update', $sala), ['nombre' => 'x'])->assertUnauthorized();
        $this->postJson(route('procedimientos.store'), ['nombre' => 'x'])->assertUnauthorized();
    }

    public function test_plantilla_customization_creates_a_clinic_specific_copy_without_affecting_others(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'tpl-a@example.com');
        [$clinicaB, $userB] = $this->clinicaConUsuario('Clínica B', 'tpl-b@example.com');

        $global = Plantilla::create([
            'clinica_id' => null,
            'clave' => 'colonoscopia',
            'nombre' => 'Colonoscopia',
            'tipo_plantilla' => 'informe',
            'configuracion' => ['anatImg' => null],
            'es_predeterminada' => true,
        ]);

        // A personaliza su copia.
        $this->actingAs($userA)
            ->postJson('/plantillas/colonoscopia', [
                'configuracion' => ['anatImg' => '/images/perro.png'],
            ])->assertOk();

        $propiaA = Plantilla::where('clave', 'colonoscopia')->where('clinica_id', $clinicaA->id)->first();
        $this->assertNotNull($propiaA);
        $this->assertSame('/images/perro.png', $propiaA->configuracion['anatImg']);

        // La plantilla global no cambió.
        $this->assertNull($global->refresh()->configuracion['anatImg']);

        // B sigue viendo la plantilla global (sin el diagrama de A).
        $resueltaParaB = Plantilla::forClave('colonoscopia', $clinicaB->id);
        $this->assertNull($resueltaParaB->configuracion['anatImg']);
        $this->assertNull($resueltaParaB->clinica_id);

        // A ve su propia copia.
        $resueltaParaA = Plantilla::forClave('colonoscopia', $clinicaA->id);
        $this->assertSame($propiaA->id, $resueltaParaA->id);
    }

    private function clinicaConUsuario(string $nombre, string $email): array
    {
        $clinica = Clinica::create(['nombre' => $nombre]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor '.$nombre,
            'email' => $email,
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        return [$clinica, $user];
    }
}
