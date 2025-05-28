<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLoginServices
{
    /**
     * @throws ValidationException
     */
    public function auth(string $email, string $password): array
    {
        $user = User::query()->where('email', $email)->first();

        if ($user and $user->password and Hash::check($password, $user->password)) {
            return [
                'token' => $user->createToken('authToken')->plainTextToken,
                'user' => UserResource::make($user),
            ];
        }

        throw  ValidationException::withMessages([
            'message' => __('auth.failed'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function register(string $email, string $password, string $name): array
    {
        $user = User::query()->where('email', $email)->first();

        if ($user){
            throw ValidationException::withMessages([
                'email' => 'Пользователь с такой почтой уже зарегистрирован',
            ]);
        }

        $user = User::query()->create([
            'email' => $email,
            'password' =>  Hash::make($password),
            'name' => $name,
        ]);

        return [
            'token' => $user->createToken('authToken')->plainTextToken,
            'user' => UserResource::make($user),
        ];
    }
}
