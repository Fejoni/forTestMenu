<?php

namespace App\Http\Controllers\Api\v1\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserUploadFileRequest;
use App\Services\User\UserUploadFileServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function upload(UserUploadFileRequest $request, UserUploadFileServices $fileServices): JsonResponse
    {
        return response()->json([
            'url' => $fileServices->upload($request->file('file')),
        ]);
    }
}
