<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class UuidModel extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
