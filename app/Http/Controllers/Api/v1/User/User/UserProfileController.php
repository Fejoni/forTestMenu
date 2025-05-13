<?php

namespace App\Http\Controllers\Api\v1\User\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserUpdateProfileRequest;
use App\Services\User\UserFamilyServices;

class UserProfileController extends Controller
{
    public function update(UserUpdateProfileRequest $request): bool
    {
        return (new UserFamilyServices(
            persons: $request->input('persons'),
            times: $request->input('selectedTimes'),
        ))->update();
    }
}
