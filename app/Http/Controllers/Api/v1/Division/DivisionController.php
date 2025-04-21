<?php

namespace App\Http\Controllers\Api\v1\Division;

use App\Http\Controllers\Controller;
use App\Http\Resources\Division\DivisionResource;
use App\Models\Division;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DivisionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DivisionResource::collection(Division::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        Division::query()->where('id', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        Division::query()->where('id', $request->get('id'))->update([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        Division::query()->create([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }
}
