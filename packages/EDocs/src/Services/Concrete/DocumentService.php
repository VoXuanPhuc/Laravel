<?php

namespace Encoda\EDocs\Services\Concrete;

use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\EDocs\Http\Requests\UploadFileRequest;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DocumentService implements DocumentServiceInterface
{


    public function upload( UploadFileRequest $request )
    {

        try {

            $file = $request->file('file');

            $filePath   = $file->store( $this->getDirPath( $request ) );
            $fileUrl    = Storage::url( $filePath );

            return [
                'name' => $file->getClientOriginalName(),
                'path' => $filePath,
                'url' => $fileUrl,
            ];
        }
        catch ( Throwable $exception ) {
            Log::error( $exception );
            throw  new ServerErrorException('Unable to upload file');
        }
    }

    protected function getDirPath( Request $request ) {
        if( $request->dir ) {
            return $request->dir;
        }

        return 'misc';
    }
    public function delete($uid)
    {
        // TODO: Implement delete() method.
    }
}
