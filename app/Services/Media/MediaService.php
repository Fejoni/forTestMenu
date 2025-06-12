<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    public function uploadMedia(UploadedFile $file, string $path): string
    {
        $storedPath = $file->store($path, 'public');

        return Storage::disk('public')->url($storedPath);
    }
}
