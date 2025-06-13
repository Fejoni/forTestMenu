<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\UuidModel;

class UserSubscription extends UuidModel
{
    protected $fillable = [
        'users_id',
        'yookassa_payment_id',
        'amount',
        'currency',
        'receipt_url',
        'valid_from',
        'valid_until',
        'status',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
