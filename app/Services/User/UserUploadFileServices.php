<?php

namespace App\Services\User;

use Illuminate\Http\UploadedFile;

class UserUploadFileServices
{
    public function upload(UploadedFile $file): string
    {
        $fileName = $file->store('dishes');
        return env('APP_URL') . '/public/storage/' . $fileName;
    }
}
