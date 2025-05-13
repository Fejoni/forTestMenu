<?php

namespace App\Http\Controllers\Api\v1\User\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

class UserCheckoutController extends Controller
{
    public function checkout(): JsonResponse
    {
        return response()->json([
            'user' => UserResource::make(auth()->user())
        ]);
    }
}
