<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image',
        'unit_id',
        'categories_id',
        'divisions_id',
    ];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class);
    }
}
