<?php

namespace App\Http\Resources\User;

use App\Models\Telegram\Family;
use App\Models\User\UserDishTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer $id
 * @property integer $telegram_id
 * @property string $name
 * @property string $role
 * @property string $email
 * @property string $password
 * @property string $adults
 * @property string $children
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $family = Family::query()
            ->where('users_id', $this->id)
            ->first();

        $selectedTimes = UserDishTime::query()
            ->where('user_id', $this->id)
            ->with('dishTime')
            ->orderBy('id', 'asc')
            ->get();

        return [
            'id' => $this->id,
            'telegram_id' => $this->telegram_id,
            'private_access'=> 0,
            'valid_until'=>$this->valid_until,
            'name' => $this->name ?? null,
            'role' => $this->role ?? 0,
            'email' => $this->email ?? null,
            'family' => $family->counts ?? 0,
            'selectedTimes' => $selectedTimes->map(function ($time) {
                return [
                    'id' => $time->dish_time_uuid,
                    'calories' => $time->calories,
                    'name' => $time->dishTime->name ?? '-',
                ];
            }) ?? 0
        ];
    }
}
