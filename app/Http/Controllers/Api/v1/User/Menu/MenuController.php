<?php

namespace App\Http\Controllers\Api\v1\User\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\Menu\ClearMenuRequest;
use App\Models\DishLeftovers;
use App\Models\Telegram\FoodMenu;
use App\Models\User\UserProducts;
use App\Services\Menu\MenuServices;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index(MenuServices $menuService)
    {
        $userId = auth()->id();
        $dates = $menuService->getDates();

        if(!FoodMenu::query()
            ->where('users_id', $userId)
            ->whereIn('day', $dates)
            ->first()){
            return response()->json(['message' => 'Меню не сгенерировано'], 403);
        }


        $menuData = $menuService->getUserMenuGroupedByDay($userId, $dates);

        return response()->json($menuData);
    }


    public function generate(): JsonResponse
    {
        $getDates = (new MenuServices())->getDates();

        if (!FoodMenu::query()->where('users_id', auth()->user()->id)->where('day', $getDates[0])->exists()) {
            (new MenuServices())->generate();

            return response()->json([
                'message' => 'Успешно сгенерировано'
            ]);
        }

        return response()->json([
            'message' => 'Меню уже сгенерировано'
        ], 401);
    }

    public function replacementGenerate(): JsonResponse
    {
        if (FoodMenu::query()->where('users_id', auth()->user()->id)->exists()) {
            FoodMenu::query()->where('users_id', auth()->user()->id)->delete();
            UserProducts::query()->where('users_id', auth()->user()->id)->delete();
            DishLeftovers::query()->where('user_id', auth()->user()->id)->delete();
        }

        (new MenuServices())->generate();

        return response()->json([
            'message' => 'Успешно сгенерировано'
        ]);
    }

    public function clearMenu(ClearMenuRequest $request): JsonResponse
    {
        FoodMenu::query()->where('users_id', auth()->id())->delete();
        DishLeftovers::query()->where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'Меню очищено'
        ]);
    }
}
