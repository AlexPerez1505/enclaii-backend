<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecuritySettingsController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {}

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'require_password_for_studies' => ['required', 'boolean'],
            'require_password_for_patients' => ['required', 'boolean'],
            'audit_sensitive_actions' => ['required', 'boolean'],
        ]);

        $settings = $request->user()->securitySetting()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated,
        );
        $request->user()->setRelation('securitySetting', $settings);

        $this->activity->record(
            'critical_permissions_updated',
            'security',
            'Actualizó sus permisos críticos',
            $request->user(),
            $validated,
            request: $request,
            force: true,
        );

        return response()->json([
            'message' => 'Permisos críticos actualizados.',
            'settings' => $request->user()->securityPreferences(),
        ]);
    }
}
