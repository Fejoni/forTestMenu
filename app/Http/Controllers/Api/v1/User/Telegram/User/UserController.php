<?php

namespace App\Http\Controllers\Api\v1\User\Telegram\User;

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

        if (!$data['isValid']) {
            $data = InitData::isValid($request->get('init_data'), '7795322093:AAF-_BnNky9yBa_u3Xl7VhoI-8H5QUUtxx0', true);
        }

        if ($data['isValid'] and auth()->user()->telegram_id == $data['data']['parsed']['user']['id']) {
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

        if (!$data['isValid']) {
            $data = InitData::isValid($request->get('init_data'), '7795322093:AAF-_BnNky9yBa_u3Xl7VhoI-8H5QUUtxx0', true);
        }

        if ($data['isValid']) {
            return response()->json($userServices->userResponse($data));
        }

        return response()->json(['status' => false], 403);
    }
}
