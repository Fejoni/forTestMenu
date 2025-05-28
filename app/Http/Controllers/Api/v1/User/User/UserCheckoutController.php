<?php

namespace App\Http\Controllers\Api\v1\User\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\AuthUserRequest;
use App\Http\Requests\v1\User\UserRegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserLoginServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;

class UserCheckoutController extends Controller
{
    public function checkout(): JsonResponse
    {
        return response()->json([
            'user' => UserResource::make(auth()->user())
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function login(AuthUserRequest $request, UserLoginServices $loginServices): JsonResponse
    {
        return response()->json($loginServices->auth($request->get('email'), $request->get('password')));
    }

    /**
     * @throws ValidationException
     */
    public function register(UserRegisterRequest $registerRequest, UserLoginServices $loginServices): JsonResponse
    {
        return response()->json(
            $loginServices->register(
                $registerRequest->get('email'),
                $registerRequest->get('password'),
                $registerRequest->get('name')
            )
        );
    }
}
