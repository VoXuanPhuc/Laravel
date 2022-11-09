<?php

namespace Encoda\EDocs\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\NotFoundException as NotFoundExceptionAlias;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\FileHelper;
use Encoda\EDocs\Http\Requests\UploadFileRequest;
use Encoda\EDocs\Models\Document;
use Encoda\EDocs\Repositories\Interfaces\DocumentRepositoryInterface;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class DocumentService implements DocumentServiceInterface
{
    public function __construct(
        protected DocumentRepositoryInterface $documentRepository
    )
    {
    }

    /**
     * @param string $uid
     *
     * @return mixed
     * @throws NotFoundExceptionAlias
     */
    public function getDocument(string $uid)
    {
        $document = $this->documentRepository->query()
            ->hasUID($uid)
            ->get()
            ->first();

        if (!$document) {
            throw new NotFoundException('Document not found');
        }

        return $document->fresh();
    }


    /**
     * @throws ServerErrorException
     */
    public function upload(UploadFileRequest $request )
    {
        try {

            $file = $request->file('file');
            $filePath   = $file->storeAs(
                $this->getDirPath( $request ),
                FileHelper::hashName( $file )
            );

            Storage::url( $filePath );
            $document = $this->documentRepository->create([
                'name' => FileHelper::getOriginalName($file),
                'path' => $filePath,
                'size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'created_by' => Auth::user()?->id
            ]);
            return $document->fresh();
        }
        catch ( Throwable $exception ) {
            Log::error( $exception );
            throw new ServerErrorException('Unable to upload file');
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     * @throws NotFoundExceptionAlias
     */
    protected function getDirPath( Request $request ) {
        $path =  'misc';
        if( $request->dir ) {
            $path = $request->dir;
        }
        return 'organization/' .  tenant()->code . '/' . $path;
    }

    /**
     * @param $uid
     *
     * @return mixed
     * @throws NotFoundExceptionAlias
     */
    public function delete($uid)
    {
        $document = $this->getDocument($uid);
        return $document->delete();
    }

    /**
     * @param Document $document
     *
     * @return bool|Document
     */
    public function cloneDocument(Document $document)
    {
        //Generate clone document path
        $fileName = basename($document->path);
        $newFileName = FileHelper::generateHashNameString(null, pathinfo(basename($document->path),PATHINFO_EXTENSION) );
        $newPath = str_replace($fileName, $newFileName, $document->path);
        //Duplicate file
        if(Storage::copy($document->path, $newPath)){
            //Clone document
            $newDocument = $document->replicate();
            $newDocument->path = $newPath;
            $newDocument->uid = Str::uuid();
            $newDocument->save();
            return $newDocument->refresh();
        }
        return false;
    }

}
