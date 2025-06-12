<?php

namespace App\Services\User;

use App\Models\Telegram\Family;
use App\Models\User;

class UserFamilyServices
{
    public function __construct(
        protected int $persons,
        protected array $times
    )
    {}

    public function update(): bool
    {
        $this->updateUserFamily();
        (new UserTimeServices($this->times))->update();

        return true;
    }

    protected function updateUserFamily(): void
    {
        if (!Family::query()->where('users_id', auth()->user()->id)->exists()) {
            Family::query()->create([
                'users_id' => auth()->user()->id,
                'counts' => $this->persons
            ]);
        } else {
            Family::query()->where('users_id', auth()->user()->id)->update([
                'counts' => $this->persons
            ]);
        }
    }
}
