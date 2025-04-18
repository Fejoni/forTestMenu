<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCheckoutController extends Controller
{
    public function checkout(): JsonResponse
    {
        return response()->json([
            'user' => UserResource::make(auth()->user())
        ]);
    }
}
