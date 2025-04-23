<?php

namespace App\Http\Controllers\Api\v1\Telegram\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Services\Telegram\User\UserCheckoutServices;
use App\Services\Telegram\User\UserServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vlsv\TelegramInitDataValidator\Validator\InitData;

class UserController extends Controller
{
    public function index(Request $request, UserServices $userServices): JsonResponse
    {
        $request->validate([
            'init_data' => ['required']
        ]);

        $check = InitData::isValid(
            $request->get('init_data'),
            env('TELEGRAM_BOT_TOKEN'),
            true
        );

        if ($check['isValid']) {
            return response()->json(
                new UserResource($userServices->updateOrCreate(auth()->user(), $check['data']['parsed']['user']))
            );
        }

        return response()->json(['status' => false], 403);
    }

    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'init_data' => ['required']
        ]);

        $check = InitData::isValid(
            $request->get('init_data'),
            env('TELEGRAM_BOT_TOKEN'),
            true
        );

        if ($check['isValid']) {
            return response()->json((new UserCheckoutServices($check['data']['parsed']['user']))->checkout());
        }

        return response()->json(['status' => false], 403);
    }
}
