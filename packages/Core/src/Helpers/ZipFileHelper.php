<?php

namespace Encoda\Core\Helpers;

use Encoda\Core\Exceptions\ServerErrorException;
use Throwable;
use ZipArchive;

class ZipFileHelper
{
    private static string $tmpZipDir = '/tmp';

    /**
     * @throws ServerErrorException
     */
    public static function make(string $zipName, array $files = [] ) {

        $zip = new ZipArchive();

        try{
            $rs = $zip->open( $zipName , ZipArchive::OVERWRITE );
            if( $rs ) {

                foreach ( $files as $file ) {
                    $zip->addFile( $file );

                }
            }
            else {
                throw new ServerErrorException('Can not open zip file');
            }
        }
        catch ( Throwable $e ) {
            throw new ServerErrorException('Created zip failed');
        }

        return $zipName;

    }

    /**
     * @param string $zipName
     * @param array $fileURLs
     * @return string
     * @throws ServerErrorException
     */
    public static function makeFromURLs( string $zipName, array $fileURLs = [] ) {

        $zip = new ZipArchive();

        //Make sure /tmp/zip dir exists
        self::makeTmpZipDir();

        $filePath = self::$tmpZipDir . "/" . $zipName;

        $rs = $zip->open( $filePath , ZipArchive::CREATE );

        if( $rs === true) {

            foreach ( $fileURLs as $fileName => $url ) {
                // Get file name
                $fileName = strlen( $fileName ) >= 0 ?  $fileName :basename( $url );

                $zip->addFromString( $fileName, file_get_contents( $url ) );

            }

            $zip->close();
        }
        else {
            throw new ServerErrorException('Can not open zip file');
        }



        return $filePath;
    }

    /**
     *
     */
    private static function makeTmpZipDir() {

        if( !file_exists( static::$tmpZipDir ) ) {
            mkdir( static::$tmpZipDir, 755, true );
        }

    }

    /**
     * @return string
     */
    public function getTmpZipDir(): string
    {
        return static::$tmpZipDir;

    }
}
