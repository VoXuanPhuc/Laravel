<?php

namespace Encoda\BCP\Services\Interfaces;

interface BCPExportingServiceInterface
{

    public function exportAll();
    public function exportRecord(string $uid);
}
