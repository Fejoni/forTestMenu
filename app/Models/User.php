<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Dish\DishTime;
use App\Models\Dish\DishType;
use App\Models\Telegram\Family;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed $created_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram_id',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class, 'users_id', 'id');
    }

    public function dishTimes(): BelongsToMany
    {
        return $this->belongsToMany(
            DishTime::class,
            'user_dish_times',
            'user_id',
            'dish_time_uuid',
            'id',
            'uuid'
        )->withPivot('calories');
    }
}
