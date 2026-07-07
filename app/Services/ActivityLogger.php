<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function record(
        string $action,
        string $category,
        string $description,
        ?Model $subject = null,
        array $metadata = [],
        ?User $user = null,
        ?Request $request = null,
        bool $force = false,
    ): ?ActivityLog {
        $request ??= request();
        $user ??= $request->user();

        if (! $force && $user && ! $user->auditSensitiveActionsEnabled()) {
            return null;
        }

        return ActivityLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'category' => $category,
            'description' => $description,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
            'metadata' => $metadata === [] ? null : $metadata,
        ]);
    }
}
