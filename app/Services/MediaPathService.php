<?php

namespace App\Services;

use App\Models\Estudio;
use App\Models\Paciente;
class MediaPathService
{
    public function clinic(int|string|null $clinicId): string
    {
        return 'clinics/'.$this->segment($clinicId ?: 'unassigned');
    }

    public function patient(Paciente|int|string $patient, int|string|null $clinicId = null): string
    {
        $patientId = $patient instanceof Paciente ? $patient->id : $patient;
        $clinicId = $clinicId ?: ($patient instanceof Paciente ? $patient->clinica_id : null);

        return $this->clinic($clinicId).'/patients/'.$this->segment($patientId);
    }

    public function patientProfile(Paciente|int|string $patient, int|string|null $clinicId = null): string
    {
        return $this->patient($patient, $clinicId).'/profile';
    }

    public function patientDocuments(Paciente|int|string $patient, int|string|null $clinicId = null): string
    {
        return $this->patient($patient, $clinicId).'/documents';
    }

    public function study(Estudio|int|string $study, Paciente|int|string|null $patient = null, int|string|null $clinicId = null): string
    {
        $studyId = $study instanceof Estudio ? $study->id : $study;
        $patient = $patient ?: ($study instanceof Estudio ? $study->paciente_id : null);
        $clinicId = $clinicId ?: ($study instanceof Estudio ? $study->clinica_id : null);

        return $this->patient($patient ?: 'unassigned', $clinicId).'/studies/'.$this->segment($studyId);
    }

    public function studyImages(Estudio|int|string $study, Paciente|int|string|null $patient = null, int|string|null $clinicId = null): string
    {
        return $this->study($study, $patient, $clinicId).'/images';
    }

    public function studyVideos(Estudio|int|string $study, Paciente|int|string|null $patient = null, int|string|null $clinicId = null): string
    {
        return $this->study($study, $patient, $clinicId).'/videos';
    }

    public function studyThumbnails(Estudio|int|string $study, Paciente|int|string|null $patient = null, int|string|null $clinicId = null): string
    {
        return $this->study($study, $patient, $clinicId).'/thumbnails';
    }

    public function studyReports(Estudio|int|string $study, Paciente|int|string|null $patient = null, int|string|null $clinicId = null): string
    {
        return $this->study($study, $patient, $clinicId).'/reports';
    }

    public function clinicLogos(int|string|null $clinicId): string
    {
        return $this->clinic($clinicId).'/logos';
    }

    public function clinicTemplates(int|string|null $clinicId): string
    {
        return $this->clinic($clinicId).'/templates';
    }

    private function segment(int|string|null $value): string
    {
        $segment = trim((string) $value);
        $segment = preg_replace('/[^A-Za-z0-9._-]+/', '-', $segment) ?? '';
        $segment = trim($segment, '-.');

        return $segment !== '' ? $segment : 'unassigned';
    }
}
