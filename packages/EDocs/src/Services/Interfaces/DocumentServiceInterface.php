<?php

namespace Encoda\EDocs\Services\Interfaces;

use Encoda\EDocs\Http\Requests\UploadFileRequest;
use Encoda\EDocs\Models\Document;

interface DocumentServiceInterface
{

    public function upload( UploadFileRequest $request );
    public function delete( $uid );
    public function getDocument(string $uid);
    public function cloneDocument(Document $document);
}
