<?php

namespace App\Services\User;

use Illuminate\Http\UploadedFile;

class UserUploadFileServices
{
    public function upload(UploadedFile $file): string
    {
        return env('APP_URL') . '/storage/' . $file->store('users', 'public');
    }
}
