<?php

namespace App\Http\Controllers\Api\v1\Unit;

use App\Http\Controllers\Controller;
use App\Http\Resources\Unit\UnitResource;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UnitController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UnitResource::collection(Unit::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        Unit::query()->where('id', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        Unit::query()->where('id', $request->get('id'))->update([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        Unit::query()->create([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }
}
