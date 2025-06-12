<?php

namespace App\Models\Telegram;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'users_id', 'counts',
    ];
}
