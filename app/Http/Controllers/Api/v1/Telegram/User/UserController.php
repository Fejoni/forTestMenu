<?php

namespace App\Http\Controllers\Api\v1\Telegram\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserUpdateRequest;
use App\Services\Telegram\User\UserServices;
use App\Services\User\UserUpdateServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Vlsv\TelegramInitDataValidator\Validator\InitData;

class UserController extends Controller
{
    public function index(Request $request, UserServices $userServices): JsonResponse
    {
        $request->validate([
            'init_data' => ['required']
        ]);

        $data = InitData::isValid($request->get('init_data'), env('TELEGRAM_BOT_TOKEN'), true);

        if ($data['isValid']) {
            return response()->json($userServices->userResponse($data, auth()->user()));
        }

        return response()->json(['status' => false], 403);
    }
    public function checkout(Request $request, UserServices $userServices): JsonResponse
    {
        $request->validate([
            'init_data' => ['required']
        ]);

        $data = InitData::isValid($request->get('init_data'), env('TELEGRAM_BOT_TOKEN'), true);

        if ($data['isValid']) {
            return response()->json($userServices->userResponse($data));
        }

        return response()->json(['status' => false], 403);
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
