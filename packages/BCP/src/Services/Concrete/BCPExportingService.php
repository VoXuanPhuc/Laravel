<?php

namespace Encoda\BCP\Services\Concrete;

use Encoda\BCP\Exports\BCPExport;
use Encoda\BCP\Exports\BCPRecordExport;
use Encoda\BCP\Models\BCP;
use Encoda\BCP\Repositories\Interfaces\BCPRepositoryInterface;
use Encoda\BCP\Services\Interfaces\BCPExportingServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\ZipFileHelper;
use Encoda\EDocs\Models\Document;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use function response;

class BCPExportingService implements BCPExportingServiceInterface
{

    public function __construct( protected BCPRepositoryInterface $bcpRepository )
    {
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportAll(): BinaryFileResponse
    {
        $fileName = 'BCPs_'. time(). '.xlsx';

        $data = $this->bcpRepository->all();

        $export = new BCPExport( $data );

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

        /** @var BCP $bcpObj */
        $bcpObj = $this->bcpRepository->findByUid( $uid );

        if( !$bcpObj ) {
            throw new NotFoundException( 'BIA Not found' );
        }

        $fileName       = "BCP_{$bcpObj->uid}_". time();
        $excelFileName  =  "{$fileName}.xlsx";
        $excelFilePath  =  $this->getFilePath( $excelFileName );
        $zipFileName    =   "{$fileName}.zip";

        $export = new BCPRecordExport( $bcpObj );

        Excel::store( $export, $excelFilePath );

        $filesToZip = [
            // Excel file first
            $excelFileName => $this->getTmpFileUrl( $excelFilePath )
        ];

        // Add report files
        /** @var Document $report */
        foreach ( $bcpObj->reports as $report ) {

            $filesToZip[$report->name] = $report->url;
        }

        $zipFilePath = ZipFileHelper::makeFromURLs( $zipFileName,  $filesToZip );

        return response()
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
        return "storage/{$tenantCode}/exports/bcp/{$fileName}";

    }
}
