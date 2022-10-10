<?php

namespace Encoda\Resource\Services\Interfaces;

use Encoda\Resource\Http\Requests\Category\CreateResourceCategoryRequest;
use Encoda\Resource\Http\Requests\Category\UpdateResourceCategoryRequest;

interface ResourceCategoryServiceInterface
{
    public function listResourceCategory();
    public function getResourceCategory( $uid );
    public function createResourceCategory( CreateResourceCategoryRequest $request );
    public function updateResourceCategory( UpdateResourceCategoryRequest $request, $uid );
    public function deleteResourceCategory( $uid );
}
