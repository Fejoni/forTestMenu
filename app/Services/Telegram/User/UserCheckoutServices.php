<?php

namespace App\Services\Telegram\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;

class UserCheckoutServices extends UserServices
{
    protected User $user;

    public function __construct(array $requestData)
    {
        $this->user = $this->updateOrCreate(
            User::query()->where('telegram_id', $requestData['id'])->first(),
            $requestData
        );
    }

    public function checkout(): array
    {
        return [
            'user' => new UserResource($this->user),
            'token' => $this->user->createToken('authToken')->plainTextToken
        ];
    }
}
