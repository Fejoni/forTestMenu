<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserLoginRequest;
use App\Services\User\UserLoginServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function login(UserLoginRequest $request, UserLoginServices $userLoginServices): JsonResponse
    {
        return response()->json(
            $userLoginServices->auth($request->get('email'), $request->get('password'))
        );
    }
}
