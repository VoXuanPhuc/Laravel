<?php

namespace Encoda\Organization\Services\Interfaces;

use Encoda\Organization\Http\Requests\Industry\IndustryRequest;

interface IndustryServiceInterface
{
    public function listIndustry( );
    public function getIndustry( $uid );
    public function createIndustry( IndustryRequest $request );
    public function updateIndustry( IndustryRequest $request, $uid );
    public function deleteIndustry( $uid );
}
