<?php

namespace Encoda\Resource\Services\Interfaces;

use Encoda\Resource\Http\Requests\Resource\CreateResourceRequest;
use Encoda\Resource\Http\Requests\Resource\UpdateResourceRequest;

interface ResourceServiceInterface
{

    public function listResource();
    public function getResource( $uid );
    public function createResource( CreateResourceRequest $request );
    public function updateResource( UpdateResourceRequest $request, $uid );
    public function deleteResource( $uid );
    public function export( $category = null, $range = 'all' );
}
