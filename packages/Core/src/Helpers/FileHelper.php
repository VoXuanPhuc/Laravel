<?php

namespace Encoda\Core\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

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

    /**
     * @param $file
     * @return mixed|string
     */
    public static function hashName( $file  )
    {

        // An error from MIMETypes was detected Doc as txt or HTML, we need to keep the original name
        if( str_contains($file->getClientMimeType(), 'msword') ) {
            return self::generateHashNameString( $file, 'docx' );
        }
        elseif( str_contains($file->getClientMimeType(), 'text/plain') ) {
            return self::generateHashNameString( $file );
        }

        return $file->hashName();
    }

    /**
     * @param $file
     * @param string $defaultExt
     * @return string
     */
    private static function generateHashNameString($file, string $defaultExt = 'txt' ): string
    {

        $hash =  Str::random(40);

        $originalExt = $file->getClientOriginalExtension() ?: $defaultExt;

        $extension = '.' . $originalExt;

        return time() . $hash . $extension;

    }
}
