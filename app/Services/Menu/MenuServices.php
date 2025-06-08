<?php

namespace App\Services\Menu;

use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishTime;
use App\Models\DishLeftovers;
use App\Models\FoodMenuDishProduct;
use App\Models\Telegram\Family;
use App\Models\Telegram\FoodMenu;
use App\Models\User\UserDishTime;
use App\Models\User\UserProducts;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class MenuServices
{
    public function getDates(): array
    {
        App::setLocale('ru');
        Carbon::setLocale('ru');

        $startOfWeek = Carbon::now()->startOfWeek();
        $period = CarbonPeriod::create($startOfWeek, Carbon::now()->endOfWeek());


        $dates = [];

        foreach ($period as $date) {
            $dates[] = mb_strtolower($date->isoFormat('dd DD.MM'));
        }

        return $dates;
    }

    public function generate(): void
    {
        $userId = auth()->id();
        $familyCount = Family::query()->where('users_id', $userId)->value('counts') ?? 1;

        $dishTimes = UserDishTime::query()
            ->where('user_id', $userId)
            ->with('dishTime')
            ->orderBy('id')
            ->get();

        $dishTimeOrder = $dishTimes->pluck('dishTime.uuid')->toArray();
        $dates = $this->getDates();

        foreach ($dates as $dateLabel) {
            $usedDishUuids = [];

            foreach ($dishTimes as $dishTimeEntry) {
                $dishTimeUuid = $dishTimeEntry->dishTime->uuid;
                $neededCalories = $dishTimeEntry->calories;
                $addedCalories = 0;
                $selectedDishes = [];

                $foodMenu = FoodMenu::create([
                    'dish_time_id' => $dishTimeUuid,
                    'users_id' => $userId,
                    'day' => $dateLabel,
                ]);

                $leftovers = DishLeftovers::query()
                    ->where('user_id', $userId)
                    ->where('dish_time_uuid', $dishTimeUuid)
                    ->get();


                foreach ($leftovers as $leftover) {

                    if ($addedCalories >= $neededCalories - 50) break;

                    $dish = Dish::with('products')->find($leftover->dish_id);
                    if (!$dish) continue;

                    $dishCalories = $dish->calories;
                    $requiredCalories = $neededCalories - $addedCalories;

                    $neededPortions = (int)ceil($requiredCalories / $dishCalories);
                    $takePortions = min($leftover->portions, $neededPortions, $familyCount);

                    $addedCalories += $dishCalories;
                    $selectedDishes[] = ['dish' => $dish, 'portions' => $takePortions];
                    $usedDishUuids[] = $dish->uuid;

                    if ($leftover->portions <= $takePortions) {
                        $leftover->delete();
                    } else {
                        $leftover->update(['portions' => $leftover->portions - $takePortions]);
                    }
                }

                while ($addedCalories < $neededCalories - 50) {
                    $dish = Dish::query()
                        ->whereHas('times', fn($q) => $q->where('dish_dish_time.time_id', $dishTimeUuid))
                        ->whereNotIn('uuid', $usedDishUuids)
                        ->inRandomOrder()
                        ->with('products')
                        ->first();

                    if (!$dish) break;

                    $actualPortions = min($dish->portions, $familyCount);
                    $addedCalories += $dish->calories;

                    $selectedDishes[] = ['dish' => $dish, 'portions' => $actualPortions];

                    $this->productsBuy($dish);

                    if ($dish->portions < $familyCount) {
                        $this->addBuyProducts($dish, ($familyCount - $dish->portions));
                    }
                    $usedDishUuids[] = $dish->uuid;

                    if ($dish->portions > $familyCount) {
                        $leftPortions = $dish->portions - $familyCount;
                        $nextTimeIndex = array_search($dishTimeUuid, $dishTimeOrder) + 1;
                        if ($nextTimeIndex >= count($dishTimeOrder)) {
                            $nextTimeIndex = 0;
                        }

                        DishLeftovers::create([
                            'user_id' => $userId,
                            'dish_id' => $dish->uuid,
                            'dish_time_uuid' => $dishTimeOrder[$nextTimeIndex],
                            'portions' => $leftPortions,
                        ]);
                    }
                }

                foreach ($selectedDishes as $item) {
                    FoodMenuDishProduct::create([
                        'food_menus_id' => $foodMenu->uuid,
                        'dish_id' => $item['dish']->uuid,
                        'portions' => $item['portions'] ?? 1,
                    ]);
                }
            }
        }
    }

    private function addBuyProducts(Dish $dish, int $neededPortions = 1)
    {
        $defaultCountDishPortions = max(1, $dish->portions);

        foreach ($dish->products as $product) {
            $portionRatio = $product->pivot->quantity / $defaultCountDishPortions;
            $additionalProducts = round($portionRatio, 1);
            $totalQuantity = $additionalProducts * $neededPortions;

            $userProduct = UserProducts::query()
                ->where([
                    ['users_id', auth()->id()],
                    ['product_id', $product->uuid]
                ])->first();

            if ($userProduct) {
                $userProduct->count = round($userProduct->count + $totalQuantity);
                $userProduct->status = false;
                $userProduct->save();
            }
        }
    }

    public function productsBuy(Dish $dish): void
    {
        if (count($dish->products) > 0) {
            foreach ($dish->products as $product) {
                $userProduct = UserProducts::query()->where([['users_id', auth()->user()->id], ['product_id', $product->uuid]])->first();

                if (!$userProduct) {
                    UserProducts::query()->create([
                        'product_id' => $product->uuid,
                        'users_id' => auth()->user()->id,
                        'count' => $product->pivot->quantity
                    ]);
                } else {
                    $userProduct->count += $product->pivot->quantity;
                    $userProduct->status = false;
                    $userProduct->save();
                }
            }
        }
    }

    public function productsBuyDelete(Dish $dish): void
    {
        foreach ($dish->products as $product) {
            $userProduct = UserProducts::query()->where([['users_id', auth()->user()->id], ['product_id', $product->uuid]])->first();

            if ($userProduct) {
                $userProduct->count -= $product->pivot->quantity;
                $userProduct->save();
            }
        }
    }

    public function menuExistsForDate(int $userId, string $date): bool
    {
        return FoodMenu::query()
            ->where('users_id', $userId)
            ->where('day', $date)
            ->exists();
    }

    public function getUserMenuGroupedByDay(int $userId, array $dates)
    {
        $menus = FoodMenu::where('users_id', $userId)
            ->whereIn('day', $dates)
            ->orderByRaw("FIELD(SUBSTRING(day, 1, 2), 'пн','вт','ср','чт','пт','сб','вс')")
            ->get();

        $dishTimes = DishTime::all()->keyBy('uuid');

        $menuProducts = FoodMenuDishProduct::query()
            ->whereIn('food_menus_id', $menus->pluck('uuid'))
            ->get()
            ->groupBy('food_menus_id');

        $dishIds = $menuProducts->flatten()->pluck('dish_id')->unique();
        $dishes = Dish::with('products')->whereIn('uuid', $dishIds)->get()->keyBy('uuid');

        return $menus->groupBy('day')->map(function ($menusForDay) use ($dishTimes, $menuProducts, $dishes) {
            $dayMeals = [];

            foreach ($menusForDay as $menu) {
                $dishTime = $dishTimes[$menu->dish_time_id] ?? null;
                if (!$dishTime) continue;

                $products = $menuProducts[$menu->uuid] ?? collect();
                if ($products->isEmpty()) continue;

                $mealDishes = $products->pluck('dish_id')->unique()->map(function ($id) use ($dishes) {
                    return new DishResource($dishes[$id]);
                })->filter();

                if ($mealDishes->isNotEmpty()) {
                    $dayMeals[$dishTime->name] = $dayMeals[$dishTime->name] ?? collect();
                    $dayMeals[$dishTime->name]->push([
                        'data' => $mealDishes->values(),
                        'id' => $menu->uuid,
                    ]);
                }
            }

            return collect(['Завтрак', 'Ланч', 'Обед', 'Полдник', 'Ужин'])
                ->filter(fn($time) => isset($dayMeals[$time]))
                ->mapWithKeys(fn($time) => [$time => $dayMeals[$time]->values()]);
        });
    }

}
