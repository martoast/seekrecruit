<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CvStorageService
{
    public function upload(UploadedFile $file, int $userId): string
    {
        $filename = 'cv_' . $userId . '_' . time() . '.pdf';
        $path = $file->storeAs('cvs', $filename, 'private');

        return $path;
    }

    public function delete(string $path): bool
    {
        return Storage::disk('private')->delete($path);
    }

    public function getSignedUrl(string $path, int $minutes = 30): string
    {
        return Storage::disk('private')->temporaryUrl(
            $path,
            now()->addMinutes($minutes)
        );
    }
}
