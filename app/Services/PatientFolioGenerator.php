<?php

namespace App\Services;

use App\Models\Paciente;

class PatientFolioGenerator
{
    public function next(int $clinicId): string
    {
        $lastNumber = Paciente::withoutGlobalScopes()
            ->where('clinica_id', $clinicId)
            ->where('folio', 'like', 'P-%')
            ->pluck('folio')
            ->map(fn (string $folio) => preg_match('/^P-(\d+)$/', $folio, $matches)
                ? (int) $matches[1]
                : 0)
            ->max() ?? 0;

        return 'P-'.str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
    }
}
