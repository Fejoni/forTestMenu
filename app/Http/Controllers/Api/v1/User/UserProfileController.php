<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserUpdateProfileRequest;
use App\Services\User\UserFamilyServices;
use Illuminate\Http\Request;

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
