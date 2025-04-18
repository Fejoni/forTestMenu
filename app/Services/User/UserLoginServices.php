<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserLoginServices
{
    public function auth(string $email, string $password): array
    {
        $user = User::query()->where('email', $email)->first();

        if (Hash::check($password, $user->password)) {
            return [
                'status' => true,
                'token' => $user->createToken('authToken')->plainTextToken,
                'user' => UserResource::make($user),
            ];
        }

        return [
            'status' => false,
            'message' => __('auth.failed'),
        ];
    }
}
