<?php

namespace App\Http\Controllers\Api\v1\User\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\v1\User\AuthUserRequest;
use App\Http\Requests\v1\User\UserRegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserLoginServices;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
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

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->email)
            ->firstOrFail();

        event(new PasswordReset($user));

        return response()->json(['message' => 'Новый пароль отправлен на почту.']);
    }
}
