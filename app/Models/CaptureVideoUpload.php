<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaptureVideoUpload extends Model
{
    protected $fillable = [
        'upload_id',
        'session_id',
        'filename',
        'mime_type',
        'total_size',
        'total_chunks',
        'received_chunks',
        'status',
        'path',
        'ended_at',
    ];

    protected $casts = [
        'received_chunks' => 'array',
        'total_size' => 'integer',
        'total_chunks' => 'integer',
        'ended_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(CaptureSession::class, 'session_id');
    }
}
