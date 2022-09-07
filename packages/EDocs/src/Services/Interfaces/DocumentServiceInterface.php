<?php

namespace Encoda\EDocs\Services\Interfaces;

use Encoda\EDocs\Http\Requests\UploadFileRequest;

interface DocumentServiceInterface
{

    public function upload( UploadFileRequest $request );
    public function delete( $uid );
}
