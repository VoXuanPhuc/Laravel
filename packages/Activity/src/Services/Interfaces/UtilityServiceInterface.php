<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface UtilityServiceInterface
{
    public function listUtilities();

    public function getUtility( $uid) ;

    public function createUtility( Request $request );

    public function updateUtility( Request $request, $uid );

    public function deleteUtility( $uid);
}
