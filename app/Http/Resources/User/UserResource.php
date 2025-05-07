<?php

namespace App\Http\Resources\User;

use App\Models\Telegram\Family;
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

        return [
            'id' => $this->id,
            'telegram_id' => $this->telegram_id,
            'name' => $this->name ?? null,
            'role' => $this->role ?? 0,
            'email' => $this->email ?? null,
            'family' => [
                'adults' => $family->adults ?? '',
                'children' => $family->children ?? '',
            ]
        ];
    }
}
