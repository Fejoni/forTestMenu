<?php

namespace App\Http\Controllers\Api\v1\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        Category::query()->where('id', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        Category::query()->where('id', $request->get('id'))->update([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        Category::query()->create([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }
}
