<?php

namespace Encoda\Resource\Services\Interfaces;

use Encoda\Resource\Http\Requests\Resource\CreateResourceRequest;
use Encoda\Resource\Http\Requests\Resource\UpdateResourceRequest;

interface ResourceServiceInterface
{

    public function listResource( $organization );
    public function getResource( $organization, $uid );
    public function createResource( CreateResourceRequest $request, $organization );
    public function updateResource( UpdateResourceRequest $request, $organization, $uid );
    public function deleteResource( $organization, $uid );
    public function export( $category = null, $range = 'all' );
}
