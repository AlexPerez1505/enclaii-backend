<?php

namespace Tests\Unit;

use App\Models\Paciente;
use App\Models\User;
use App\Services\MediaPathService;
use PHPUnit\Framework\TestCase;

class MediaPathServiceTest extends TestCase
{
    public function test_it_builds_canonical_media_paths(): void
    {
        $paths = new MediaPathService;
        $patient = new Paciente(['clinica_id' => 4]);
        $patient->id = 110;
        $user = new User(['clinica_id' => 4]);
        $user->id = 7;

        $this->assertSame(
            'clinics/4/patients/110/studies/45/images',
            $paths->studyImages(45, $patient)
        );
        $this->assertSame(
            'clinics/4/patients/110/documents',
            $paths->patientDocuments($patient)
        );
        $this->assertSame(
            'clinics/4/users/7/profile',
            $paths->userProfile($user)
        );
        $this->assertSame(
            'clinics/4/users/7/tax-documents',
            $paths->userTaxDocuments($user)
        );
        $this->assertStringStartsWith(
            'clinics/4/users/7/ai-uploads/',
            $paths->userAiUploads($user)
        );
        $this->assertSame(
            'clinics/4/preregistrations/photos',
            $paths->patientPreregistrationPhotos(4)
        );
    }
}
