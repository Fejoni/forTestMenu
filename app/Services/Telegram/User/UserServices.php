<?php

namespace App\Services\Telegram\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;

class UserServices
{
    public function userResponse(array $data, ?User $user = null): array
    {
        $newUser = $this->updateOrCreate(
            user: $user ?? User::query()->where('telegram_id', $data['data']['parsed']['user']['id'])->first(),
            data: $data['data']['parsed']['user']
        );

        $response = [
            'user' => new UserResource(
                User::query()
                    ->where('id', $newUser['user']->id)
                    ->with(['family'])
                    ->first(),
            ),
            'first_connect' => $newUser['status'],
        ];

        if (!$user) {
            $response['token'] = $newUser['user']->createToken('authToken')->plainTextToken;
        }

        return $response;
    }

    public function updateOrCreate(?User $user, array $data): array
    {
        $data = [
            'telegram_id' => $data['id'],
        ];

        $status = false;

        if (!$user) {
            $user = User::query()->create($data);

            $status = true;
        }

        return [
            'user' => $user,
            'status' => $status,
        ];
    }
}
