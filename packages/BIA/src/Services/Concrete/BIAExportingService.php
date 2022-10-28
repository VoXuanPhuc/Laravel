<?php

namespace Encoda\BIA\Services\Concrete;

use Encoda\BIA\Exports\BIAExport;
use Encoda\BIA\Exports\BIARecordExport;
use Encoda\BIA\Models\BIA;
use Encoda\BIA\Repositories\Interfaces\BIARepositoryInterface;
use Encoda\BIA\Services\Interfaces\BIAExportingServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\ZipFileHelper;
use Encoda\EDocs\Models\Document;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BIAExportingService implements BIAExportingServiceInterface
{

    public function __construct( protected BIARepositoryInterface $biaRepository )
    {
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportAll(): BinaryFileResponse
    {
        $fileName = 'BIAs_'. time(). '.xlsx';

        $data = $this->biaRepository->all();

        $export = new BIAExport( $data );

        return Excel::download( $export, $fileName )
            ->deleteFileAfterSend( false );
    }


    /**
     * @param string $uid
     * @return BinaryFileResponse
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function exportRecord(string $uid): BinaryFileResponse
    {

        /** @var BIA $biaObj */
        $biaObj = $this->biaRepository->findByUid( $uid );

        if( !$biaObj ) {
            throw new NotFoundException( 'BIA Not found' );
        }

        $fileName       = "BIA_{$biaObj->uid}_". time();
        $excelFileName  =  "{$fileName}.xlsx";
        $excelFilePath  =  $this->getFilePath( $excelFileName );
        $zipFileName    =   "{$fileName}.zip";

        $export = new BIARecordExport( $biaObj );

        Excel::store( $export, $excelFilePath );

        $filesToZip = [
            // Excel file first
            $excelFileName => $this->getTmpFileUrl( $excelFilePath )
        ];

        // Add report files
        /** @var Document $report */
        foreach ( $biaObj->reports as $report ) {

            $filesToZip[$report->name] = $report->url;
        }

        $zipFilePath = ZipFileHelper::makeFromURLs( $zipFileName,  $filesToZip );

        return \response()
            ->download( $zipFilePath )
            ->deleteFileAfterSend(false);
    }

    /**
     * @param $fileName
     * @return string
     */
    private function getTmpFileUrl($fileName ): string
    {

        return Storage::temporaryUrl(
            $fileName,
            Date::now()->addMinutes(config('filesystems.temporary_url_ttl') )
        );
    }


    /**
     * @param $fileName
     * @return string
     * @throws NotFoundException
     */
    private function getFilePath( $fileName ): string
    {
        $tenantCode = tenant()->code;
        return "storage/{$tenantCode}/exports/bia/{$fileName}";

    }
}
