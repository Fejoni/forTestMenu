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
        $this->updateFamilyQuery($data);

        return 200;
    }

    protected function updateUserQuery(array $data): void
    {
        User::query()->where('id', auth()->user()->id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    private function updateFamilyQuery(array $data): void
    {
        if (!Family::query()->where('users_id', auth()->user()->id)->exists()) {
            Family::query()->create([
                'users_id' => auth()->user()->id,
                'counts' => $data['adults'],
            ]);
        } else {
            Family::query()->where('users_id', auth()->user()->id)->update([
                'counts' => $data['adults'],
            ]);
        }
    }
}
