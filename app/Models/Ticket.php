<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'description',
        'status',
        'priority',
        'business_name',
        'tax_address',
        'rfc',
        'operation_folio',
        'operation_datetime',
        'concepts',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'totals',
        'attachment_path',
    ];
}
