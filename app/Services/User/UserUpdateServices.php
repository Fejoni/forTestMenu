<?php

namespace App\Services\User;

use App\Models\Telegram\Family;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserUpdateServices
{
    /**
     * @throws ValidationException
     */
    public function update(array $data): int
    {
        if (auth()->user()->email !== $data['email'] and User::query()->where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Данная почта уже используется',
            ]);
        }

        $this->updateUserQuery($data);

        (new UserFamilyServices(
            persons: $data['family'],
            times: $data['selectedTimes']
        ))->update();

        return 200;
    }

    protected function updateUserQuery(array $data): void
    {
        User::query()->where('id', auth()->user()->id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => isset($data['password']) ? Hash::make($data['password']) : null,
        ]);
    }
}
