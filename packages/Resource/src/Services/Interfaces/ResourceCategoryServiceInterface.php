<?php

namespace Encoda\Resource\Services\Interfaces;

use Encoda\Resource\Http\Requests\Category\CreateResourceCategoryRequest;
use Encoda\Resource\Http\Requests\Category\UpdateResourceCategoryRequest;

interface ResourceCategoryServiceInterface
{
    public function listResourceCategory( $organization );
    public function getResourceCategory( $organization, $uid );
    public function createResourceCategory( CreateResourceCategoryRequest $request, $organization );
    public function updateResourceCategory( UpdateResourceCategoryRequest $request, $organization, $uid );
    public function deleteResourceCategory( $organization, $uid );
}
