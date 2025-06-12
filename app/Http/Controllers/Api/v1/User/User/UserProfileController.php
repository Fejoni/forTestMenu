<?php

namespace App\Http\Controllers\Api\v1\User\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserUpdateProfileRequest;
use App\Http\Requests\v1\User\UserUpdateRequest;
use App\Services\User\UserFamilyServices;
use App\Services\User\UserUpdateServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function updateFirstView(UserUpdateProfileRequest $request): bool
    {
        return (new UserFamilyServices(
            persons: $request->input('persons'),
            times: $request->input('selectedTimes'),
        ))->update();
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
