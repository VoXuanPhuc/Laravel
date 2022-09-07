<?php

namespace Encoda\EDocs\Http\Controllers;

use Encoda\EDocs\Http\Requests\UploadFileRequest;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;

class DocumentController extends Controller
{

    public function __construct( protected DocumentServiceInterface $documentService )
    {
    }

    public function upload( UploadFileRequest $request ) {
        return $this->documentService->upload( $request );
    }
}
