<?php

namespace App\Http\Controllers\Api\v1\Telegram;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Telegram\Family\FamilyAddRequest;
use App\Models\Telegram\Family;
use Illuminate\Http\JsonResponse;

class FamilyController extends Controller
{
    public function addFamily(FamilyAddRequest $request): JsonResponse
    {
        Family::query()
            ->updateOrCreate(
                ['users_id' => auth()->id()],
                [
                    'users_id' => auth()->id(),
                    'count' => $request->input('count'),
                ]
            );

        return response()->json(['success' => true]);
    }

    public function status(): JsonResponse
    {
        return response()->json([
            'status' => (bool)Family::query()->where('users_id', auth()->id())->first(),
        ]);
    }
}
