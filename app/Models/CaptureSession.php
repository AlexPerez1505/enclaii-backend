<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaptureSession extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'paciente_id',
        'estudio_id',
        'study_id',
        'capture_device_id',
        'status',
        'live_frame_path',
        'live_frame_at',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'live_frame_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(CaptureDevice::class, 'capture_device_id');
    }
}