<?php

namespace Encoda\Activity\Services\Interfaces;

use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;

interface ActivityRemoteAccessServiceInterface
{

    /**
     * @param SaveRemoteAccessRequest $request
     * @param $activityUid
     * @return mixed
     */
    public function saveRemoteAccessFactors( SaveRemoteAccessRequest $request, $activityUid );
}
