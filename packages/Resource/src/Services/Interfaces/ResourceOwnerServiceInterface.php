<?php

namespace Encoda\Resource\Services\Interfaces;

use Encoda\Resource\Http\Requests\Owner\CreateResourceOwnerRequest;
use Encoda\Resource\Http\Requests\Owner\UpdateResourceOwnerRequest;

interface ResourceOwnerServiceInterface
{

    public function listResourceOwner();
    public function listAllResourceOwner();
    public function getResourceOwner( $uid );
    public function createResourceOwner( CreateResourceOwnerRequest $request );
    public function updateResourceOwner( UpdateResourceOwnerRequest $request, $uid );
    public function deleteResourceOwner( $uid );
}
