<?php

namespace App\Services\User;

use App\Models\User;

class UserTimeServices
{

    public function __construct(
        protected array $times
    )
    {}

    public function update(): void
    {
        $user = User::query()->where('id', auth()->user()->id)->first();

        $user->dishTimes()->detach();

        $user->dishTimes()->sync(
            collect($this->times)->mapWithKeys(function ($time) {
                return [
                    $time['id'] => ['calories' => $time['calories']]
                ];
            })->all()
        );
    }
}
