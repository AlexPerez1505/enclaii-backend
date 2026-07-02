<?php

namespace App\Http\Controllers;

use App\Models\ConfigurationBackup;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\ConfigurationBackupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConfigurationBackupController extends Controller
{
    public function __construct(
        private readonly ConfigurationBackupService $backups,
        private readonly ActivityLogger $activity,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'mode' => ['required', 'in:complete,custom'],
            'scope' => ['required_if:mode,custom', 'array'],
            'scope.*' => ['string', 'in:general,profile'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $scopes = $validated['mode'] === 'complete'
            ? ConfigurationBackupService::SCOPES
            : $validated['scope'];

        $backup = $this->backups->create($user, $validated['name'], $scopes);
        $this->activity->record(
            'backup_created',
            'backups',
            'Creó una copia de configuración',
            $backup,
            user: $user,
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Copia creada correctamente.',
            'backup' => $this->backupData($backup),
        ], 201);
    }

    public function restore(Request $request, int $backup): JsonResponse
    {
        $configurationBackup = $this->ownedBackup($request, $backup);
        $rollback = $this->backups->restore($request->user(), $configurationBackup);
        $this->activity->record(
            'backup_restored',
            'backups',
            'Restauró una copia de configuración',
            $configurationBackup,
            user: $request->user(),
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Configuración restaurada correctamente.',
            'rollback_backup' => $this->backupData($rollback),
        ]);
    }

    public function download(Request $request, int $backup): StreamedResponse
    {
        $configurationBackup = $this->ownedBackup($request, $backup);
        $contents = json_encode([
            'format' => 'enclaii-configuration-backup',
            'version' => $configurationBackup->version,
            'name' => $configurationBackup->name,
            'created_at' => $configurationBackup->created_at?->toIso8601String(),
            'scope' => $configurationBackup->scope,
            'configuration' => $configurationBackup->payload,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $filename = Str::slug($configurationBackup->name ?: 'configuracion')
            .'-'.$configurationBackup->created_at->format('Ymd-His').'.json';

        return response()->streamDownload(
            static fn () => print $contents,
            $filename,
            ['Content-Type' => 'application/json; charset=UTF-8'],
        );
    }

    public function destroy(Request $request, int $backup): JsonResponse
    {
        $configurationBackup = $this->ownedBackup($request, $backup);
        $this->activity->record(
            'backup_deleted',
            'backups',
            'Eliminó una copia de configuración',
            $configurationBackup,
            user: $request->user(),
            request: $request,
        );
        $configurationBackup->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Copia eliminada correctamente.',
        ]);
    }

    private function ownedBackup(Request $request, int $backup): ConfigurationBackup
    {
        return $request->user()
            ->configurationBackups()
            ->whereKey($backup)
            ->firstOrFail();
    }

    private function backupData(ConfigurationBackup $backup): array
    {
        return [
            'id' => $backup->id,
            'name' => $backup->name,
            'type' => $backup->type,
            'scope' => $backup->scope,
            'status' => $backup->status,
            'size' => $backup->size,
            'created_at' => $backup->created_at?->toIso8601String(),
        ];
    }
}
