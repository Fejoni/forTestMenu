<?php

namespace App\Models\Product;

use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends UuidModel
{
    protected $primaryKey = 'uuid';

    protected $fillable = ['name'];
}
