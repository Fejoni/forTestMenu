<?php

namespace App\Http\Controllers\Api\v1\User\Dish;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dish\DishTimeResource;
use App\Models\Dish\DishTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishController extends Controller
{
    public function time(): AnonymousResourceCollection
    {
        return DishTimeResource::collection(DishTime::query()->get());
    }

    public function timeDefaultSelect(): AnonymousResourceCollection
    {
        return DishTimeResource::collection(
            DishTime::query()
                ->where('name', 'Завтрак')
                ->orWhere('name', 'Обед')
                ->orWhere('name', 'Ужин')
                ->get()
        );
    }
}
