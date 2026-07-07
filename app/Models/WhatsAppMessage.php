<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessage extends Model
{
    use BelongsToClinica;

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'clinica_id',
        'paciente_id',
        'user_id',
        'meta_message_id',
        'wa_id',
        'direction',
        'type',
        'body',
        'status',
        'error',
        'sent_at',
        'delivered_at',
        'read_at',
        'failed_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
