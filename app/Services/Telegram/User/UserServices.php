<?php

namespace App\Services\Telegram\User;

use App\Models\User;

class UserServices
{
    public function updateOrCreate(?User $user, array $data): User
    {
        $data = [
            'telegram_id' => $data['id'],
        ];

        if (!$user) {
            $user = User::query()->create($data);
        }

        return $user;
    }
}
