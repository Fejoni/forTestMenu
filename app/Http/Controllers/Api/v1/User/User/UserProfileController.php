<?php

namespace App\Http\Controllers\Api\v1\User\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserUpdateProfileRequest;
use App\Http\Requests\v1\User\UserUpdateRequest;
use App\Models\Dish\DishTime;
use App\Services\User\UserFamilyServices;
use App\Services\User\UserUpdateServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function updateFirstView(UserUpdateProfileRequest $request): bool
    {
        $data = $request->validated();

        $weight = (int) $data['weight'];
        $height = (int) $data['height'];
        $age = (int) $data['age'];
        $gender = $data['gender'];
        $activity = $data['activity'] ?? 'low';
        $countPerDay = (int)($data['count_per_day'] ?? 3);

        $bmr = $gender === 'female'
            ? 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age)
            : 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);

        $activityRatio = match ($activity) {
            'medium' => 1.375,
            'high' => 1.55,
            'hard' => 1.725,
            default => 1.2,
        };

        $dailyCalories = $bmr * $activityRatio;

        $ratios = match ($countPerDay) {
            5 => [
                'Завтрак' => 0.20,
                'Ланч' => 0.10,
                'Обед' => 0.35,
                'Полдник' => 0.15,
                'Ужин' => 0.20,
            ],
            4 => [
                'Завтрак' => 0.25,
                'Обед' => 0.35,
                'Полдник' => 0.15,
                'Ужин' => 0.25,
            ],
            2 => [
                'Обед' => 0.50,
                'Ужин' => 0.50,
            ],
            1 => [
                'Обед' => 1.0,
            ],
            default => [
                'Завтрак' => 0.30,
                'Обед' => 0.40,
                'Ужин' => 0.30,
            ],
        };

        $times = [];
        foreach ($ratios as $name => $ratio) {
            $uuid = DishTime::query()->where('name', $name)->value('uuid');
            if ($uuid) {
                $times[] = [
                    'id' => $uuid,
                    'calories' => (int) round($dailyCalories * $ratio),
                ];
            }
        }

        (new UserFamilyServices(
            persons: (int)($data['count_persons'] ?? 1),
            times: $times,
        ))->update();

        $request->user()->update([
            'weight' => $weight ?: null,
            'height' => $height ?: null,
            'age' => $age ?: null,
            'gender' => $gender,
            'activity' => $activity,
            'user_task' => $data['user_task'] ?? null,
            'check_privacy' => true,
            'start_setting_page_view' => true,
        ]);

        return true;
    }

    /**
     * @throws ValidationException
     */
    public function update(UserUpdateRequest $request, UserUpdateServices $updateServices): JsonResponse
    {
        return response()->json([
            'status' => $updateServices->update($request->validated())
        ]);
    }
}
