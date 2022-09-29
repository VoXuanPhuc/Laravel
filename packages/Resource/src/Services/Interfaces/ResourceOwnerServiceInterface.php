<?php

namespace Encoda\Resource\Services\Interfaces;

use Encoda\Resource\Http\Requests\Owner\CreateResourceOwnerRequest;
use Encoda\Resource\Http\Requests\Owner\UpdateResourceOwnerRequest;

interface ResourceOwnerServiceInterface
{

    public function listResourceOwner( $organization );
    public function listAllResourceOwner( $organization );
    public function getResourceOwner( $organization, $uid );
    public function createResourceOwner( CreateResourceOwnerRequest $request, $organization );
    public function updateResourceOwner( UpdateResourceOwnerRequest $request, $organization, $uid );
    public function deleteResourceOwner( $organization, $uid );
}
