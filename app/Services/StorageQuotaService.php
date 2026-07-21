<?php

namespace App\Services;

use App\Models\AiAttachment;
use App\Models\ConfigurationBackup;
use App\Models\EstudioArchivo;
use App\Models\PacienteDocumento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StorageQuotaService
{
    private const BYTES_PER_GB = 1073741824;

    private const PLAN_STORAGE_PER_PERSON_GB = [
        'clinica' => 5,
        'hospital' => 10,
        'red_medica' => 15,
    ];

    private const PLAN_LABELS = [
        'clinica' => 'Clínica',
        'hospital' => 'Hospital',
        'red_medica' => 'Red Médica',
    ];

    public function summaryFor(User $user, ?Collection $clinicMembers = null): array
    {
        $billingUser = $user->billingUser();
        $plan = str_replace('-', '_', $billingUser->stripe_plan ?: 'clinica');
        $clinicId = $user->clinica_id;
        $personCount = $this->personCount($user, $clinicMembers);
        $quotaPerPersonGb = self::PLAN_STORAGE_PER_PERSON_GB[$plan] ?? 0;
        $quotaBytes = $quotaPerPersonGb * $personCount * self::BYTES_PER_GB;

        $imagesBytes = $clinicId ? (int) EstudioArchivo::withoutGlobalScopes()
            ->where('clinica_id', $clinicId)
            ->where('tipo', 'imagen')
            ->sum('size_bytes') : 0;

        $videosBytes = $clinicId ? (int) EstudioArchivo::withoutGlobalScopes()
            ->where('clinica_id', $clinicId)
            ->where('tipo', 'video')
            ->sum('size_bytes') : 0;

        $studyOtherBytes = $clinicId ? (int) EstudioArchivo::withoutGlobalScopes()
            ->where('clinica_id', $clinicId)
            ->whereNotIn('tipo', ['imagen', 'video'])
            ->sum('size_bytes') : 0;

        $patientDocumentsBytes = $clinicId ? (int) PacienteDocumento::query()
            ->whereHas('paciente', fn ($query) => $query->withoutGlobalScopes()->where('clinica_id', $clinicId))
            ->sum('size_bytes') : 0;

        $aiAttachmentBytes = $clinicId ? (int) AiAttachment::query()
            ->whereHas('message.conversation.user', fn ($query) => $query->where('clinica_id', $clinicId))
            ->sum('size') : 0;

        $backupBytes = $clinicId ? (int) ConfigurationBackup::query()
            ->whereHas('user', fn ($query) => $query->where('clinica_id', $clinicId))
            ->sum('size') : 0;

        $otherBytes = $studyOtherBytes + $patientDocumentsBytes + $aiAttachmentBytes + $backupBytes;
        $usedBytes = $imagesBytes + $videosBytes + $otherBytes;
        $availableBytes = max(0, $quotaBytes - $usedBytes);
        $usedPercent = $quotaBytes > 0 ? min(100, round(($usedBytes / $quotaBytes) * 100, 1)) : 0.0;

        return [
            'plan' => $plan,
            'plan_label' => self::PLAN_LABELS[$plan] ?? 'Sin plan',
            'person_count' => $personCount,
            'quota_per_person_gb' => $quotaPerPersonGb,
            'quota_bytes' => $quotaBytes,
            'quota_gb' => $this->bytesToGb($quotaBytes),
            'used_bytes' => $usedBytes,
            'used_gb' => $this->bytesToGb($usedBytes),
            'available_bytes' => $availableBytes,
            'available_gb' => $this->bytesToGb($availableBytes),
            'used_percent' => $usedPercent,
            'categories' => [
                'images' => $this->category('Imágenes', $imagesBytes, $usedBytes),
                'videos' => $this->category('Videos', $videosBytes, $usedBytes),
                'other' => $this->category('Otros archivos', $otherBytes, $usedBytes),
            ],
            'history' => $this->history($clinicId, $quotaBytes),
            'recommendation' => $this->recommendation($usedPercent),
            'plans' => $this->planOptions($personCount),
        ];
    }

    private function personCount(User $user, ?Collection $clinicMembers): int
    {
        if ($clinicMembers instanceof Collection) {
            return max(1, $clinicMembers->count());
        }

        return max(1, (int) $user->clinica?->usuarios()->count());
    }

    private function planOptions(int $personCount): array
    {
        return collect(self::PLAN_STORAGE_PER_PERSON_GB)
            ->map(fn (int $gb, string $plan) => [
                'id' => $plan,
                'label' => self::PLAN_LABELS[$plan],
                'gb_per_person' => $gb,
                'total_gb_for_current_people' => $gb * max(1, $personCount),
            ])
            ->all();
    }

    private function category(string $label, int $bytes, int $usedBytes): array
    {
        return [
            'label' => $label,
            'bytes' => $bytes,
            'gb' => $this->bytesToGb($bytes),
            'percent' => $usedBytes > 0 ? (int) round(($bytes / $usedBytes) * 100) : 0,
        ];
    }

    private function history(?int $clinicId, int $quotaBytes): array
    {
        if (! $clinicId) {
            return [];
        }

        return collect(range(5, 0))
            ->map(function (int $monthsAgo) use ($clinicId, $quotaBytes) {
                $month = now()->subMonths($monthsAgo)->startOfMonth();
                $nextMonth = $month->copy()->addMonth();
                $studyBytes = (int) EstudioArchivo::withoutGlobalScopes()
                    ->where('clinica_id', $clinicId)
                    ->where('created_at', '<', $nextMonth)
                    ->sum('size_bytes');

                $patientDocumentsBytes = (int) PacienteDocumento::query()
                    ->whereHas('paciente', fn ($query) => $query->withoutGlobalScopes()->where('clinica_id', $clinicId))
                    ->where('created_at', '<', $nextMonth)
                    ->sum('size_bytes');

                $aiAttachmentBytes = (int) AiAttachment::query()
                    ->whereHas('message.conversation.user', fn ($query) => $query->where('clinica_id', $clinicId))
                    ->where('created_at', '<', $nextMonth)
                    ->sum('size');

                $backupBytes = (int) ConfigurationBackup::query()
                    ->whereHas('user', fn ($query) => $query->where('clinica_id', $clinicId))
                    ->where('created_at', '<', $nextMonth)
                    ->sum('size');

                $bytes = $studyBytes + $patientDocumentsBytes + $aiAttachmentBytes + $backupBytes;

                return [
                    'label' => Carbon::parse($month)->locale('es')->translatedFormat('M y'),
                    'bytes' => $bytes,
                    'gb' => $this->bytesToGb($bytes),
                    'percent_of_quota' => $quotaBytes > 0 ? min(100, round(($bytes / $quotaBytes) * 100, 1)) : 0,
                ];
            })
            ->all();
    }

    private function recommendation(float $usedPercent): array
    {
        if ($usedPercent >= 90) {
            return [
                'title' => 'Almacenamiento casi lleno',
                'message' => 'Libera archivos pesados o actualiza tu plan para evitar interrupciones en nuevas capturas.',
                'tone' => 'danger',
            ];
        }

        if ($usedPercent >= 75) {
            return [
                'title' => 'Revisa tus videos pesados',
                'message' => 'Tu uso está creciendo. Revisa videos largos y documentos antiguos para mantener margen disponible.',
                'tone' => 'warning',
            ];
        }

        return [
            'title' => 'Almacenamiento bajo control',
            'message' => 'Tu consumo actual está dentro del límite de tu plan.',
            'tone' => 'ok',
        ];
    }

    public function bytesToGb(int $bytes): float
    {
        return round($bytes / self::BYTES_PER_GB, 2);
    }
}
