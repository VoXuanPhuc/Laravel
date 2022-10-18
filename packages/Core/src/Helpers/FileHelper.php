<?php

namespace Encoda\Core\Helpers;

use Illuminate\Http\UploadedFile;

class FileHelper
{
    /**
     * Get file original name with extension
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public static function getOriginalName(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        if ($extension) {
            return $originalName;
        }

        return $originalName . '.' . $file->extension();
    }
}
