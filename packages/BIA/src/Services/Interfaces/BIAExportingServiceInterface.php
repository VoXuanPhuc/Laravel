<?php

namespace Encoda\BIA\Services\Interfaces;

interface BIAExportingServiceInterface
{

    public function exportAll();
    public function exportRecord(string $uid);
}
