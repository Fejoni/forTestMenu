<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserLoginRequest;
use App\Services\User\UserLoginServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function login(UserLoginRequest $request, UserLoginServices $userLoginServices): JsonResponse
    {
        return response()->json(
            $userLoginServices->auth($request->get('email'), $request->get('password'))
        );
    }
}
