<?php

namespace Database\Seeders;

use App\Models\Paciente;
use App\Models\User;
use App\Services\PatientFolioGenerator;
use Illuminate\Database\Seeder;

class PacientesDemoSeeder extends Seeder
{
    /**
     * Email del usuario propietario de la clínica donde se crearán los pacientes.
     * Cámbialo si necesitas generar los datos para otro usuario/clínica.
     */
    private const OWNER_EMAIL = 'al222410852@gmail.com';

    private const NOMBRES = [
        'Juan', 'María', 'José', 'Ana', 'Luis', 'Laura', 'Carlos', 'Sofía',
        'Miguel', 'Fernanda', 'Jorge', 'Paola', 'Ricardo', 'Daniela', 'Alejandro',
        'Valeria', 'Roberto', 'Gabriela', 'Francisco', 'Andrea', 'Diego', 'Camila',
        'Eduardo', 'Patricia', 'Sergio', 'Mónica', 'Raúl', 'Claudia', 'Fernando',
        'Verónica', 'Arturo', 'Isabel', 'Manuel', 'Rosa', 'Pablo', 'Silvia',
        'Héctor', 'Elena', 'Óscar', 'Carmen',
    ];

    private const APELLIDOS = [
        'García', 'Rodríguez', 'Martínez', 'Hernández', 'López', 'González',
        'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez',
        'Díaz', 'Reyes', 'Cruz', 'Morales', 'Ortiz', 'Gutiérrez', 'Chávez',
        'Ramos', 'Vázquez', 'Castillo', 'Jiménez', 'Romero', 'Álvarez', 'Mendoza',
        'Aguilar', 'Ruiz', 'Herrera',
    ];

    private const PROCEDIMIENTOS = [
        'Colonoscopia', 'Gastroscopia', 'Duodenoscopia', 'Broncoscopia',
    ];

    private const MEDICOS = [
        'Dr. Alberto Vega', 'Dra. Carmen Solís', 'Dr. Emilio Duarte',
        'Dra. Beatriz León', 'Dr. Rodrigo Núñez',
    ];

    public function run(): void
    {
        $owner = User::query()->where('email', self::OWNER_EMAIL)->first();

        $clinicaId = $owner?->clinica_id;

        if (! $clinicaId) {
            $this->command?->error('No se encontró un usuario con email '.self::OWNER_EMAIL.'. No se generaron pacientes.');
            return;
        }

        $folioGenerator = new PatientFolioGenerator();

        for ($i = 0; $i < 100; $i++) {
            $nombre = self::NOMBRES[array_rand(self::NOMBRES)];
            $apellidoPaterno = self::APELLIDOS[array_rand(self::APELLIDOS)];
            $apellidoMaterno = self::APELLIDOS[array_rand(self::APELLIDOS)];
            $sexo = random_int(0, 1) === 0 ? 'masculino' : 'femenino';

            $edad = random_int(18, 85);
            $fechaNacimiento = now()->subYears($edad)->subDays(random_int(0, 364));

            $identificacion = 'ID'.str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $telefono = '55'.str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $email = strtolower($nombre.'.'.$apellidoPaterno.$i).'@ejemplo.com';

            Paciente::withoutGlobalScopes()->create([
                'clinica_id' => $clinicaId,
                'folio' => $folioGenerator->next($clinicaId),
                'nombre_completo' => "{$nombre} {$apellidoPaterno} {$apellidoMaterno}",
                'identificacion' => $identificacion,
                'fecha_nacimiento' => $fechaNacimiento->toDateString(),
                'edad' => $edad,
                'peso' => random_int(50, 100),
                'altura' => round(random_int(150, 190) / 100, 2),
                'sexo' => $sexo,
                'direccion' => 'Calle Falsa '.random_int(1, 999).', Col. Centro',
                'telefono' => $telefono,
                'email' => $email,
                'medico' => self::MEDICOS[array_rand(self::MEDICOS)],
                'procedimiento' => self::PROCEDIMIENTOS[array_rand(self::PROCEDIMIENTOS)],
                'enfermedad' => null,
                'alergias' => null,
                'enfermedades' => null,
                'medicamentos_actuales' => null,
                'antecedentes_medicos' => null,
            ]);
        }

        $this->command?->info('Se crearon 100 pacientes de prueba para la clínica ID '.$clinicaId.'.');
    }
}
