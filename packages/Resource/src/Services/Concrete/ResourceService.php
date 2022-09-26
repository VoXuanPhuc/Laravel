<?php

namespace Encoda\Resource\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Organization\Models\Organization;
use Encoda\Resource\Exports\ResourceExport;
use Encoda\Resource\Http\Requests\Resource\CreateResourceRequest;
use Encoda\Resource\Http\Requests\Resource\UpdateResourceRequest;
use Encoda\Resource\Models\Resource;
use Encoda\Resource\Repositories\Interfaces\ResourceOwnerRepositoryInterface;
use Encoda\Resource\Repositories\Interfaces\ResourceRepositoryInterface;
use Encoda\Resource\Services\Interfaces\ResourceCategoryServiceInterface;
use Encoda\Resource\Services\Interfaces\ResourceOwnerServiceInterface;
use Encoda\Resource\Services\Interfaces\ResourceServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ResourceService implements ResourceServiceInterface
{

    public function __construct(
        protected ResourceRepositoryInterface $resourceRepository,
        protected ResourceCategoryServiceInterface $categoryService,
        protected ResourceOwnerServiceInterface $ownerService,
        protected ResourceOwnerRepositoryInterface $ownerRepository
    )
    {
    }

    /**
     * @param Organization $organization
     * @return mixed
     */
    public function listResource($organization)
    {
        return $organization->resources()->with('category')->paginate(config('config.pagination_size'));
    }

    public function getResource($organization, $uid)
    {
        /** @var Resource $resource */
        $resource = $this->resourceRepository->findByUid( $uid );

        if( !$resource ) {
            throw new NotFoundException( 'Unable to get resource detail' );
        }

        return $resource->load('owners', 'category' );
    }

    /**
     * @param CreateResourceRequest $request
     * @param $organization
     * @return mixed
     * @throws ServerErrorException
     */
    public function createResource(CreateResourceRequest $request, $organization)
    {

        $category = $this->categoryService->getResourceCategory( $organization, $request->category['uid']);
        $owners = $this->getOwners( $request );

        $request->merge(
            [
                'resources_category_id' => $category->id,
            ]
        );

        try {

            DB::beginTransaction();

            /** @var Resource $resource */
            $resource = $this->resourceRepository->create( $request->all() );

            $resource->owners()->sync( $owners );

            DB::commit();

        }
        catch ( Throwable $e ) {

            DB::rollBack();
            Log::error( $e );
            throw new ServerErrorException( 'Oops! Create resource error' );
        }

        return $resource;
    }

    /**
     * @param UpdateResourceRequest $request
     * @param $organization
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateResource(UpdateResourceRequest $request, $organization, $uid)
    {
        $resource = $this->getResource( $organization, $uid );

        $category = $this->categoryService->getResourceCategory( $organization, $request->category['uid']);
        $owners = $this->getOwners( $request );

        $request->merge( [ 'resources_category_id' => $category->id, ] );

        try {

            DB::beginTransaction();
            /** @var Resource $resourceUpdated */
            $resourceUpdated = $this->resourceRepository->update( $request->all(), $resource->id );
            $resourceUpdated->owners()->sync( $owners );

            DB::commit();
        }
        catch ( Throwable $e ) {
            DB::rollBack();
            Log::error( $e );
            throw new ServerErrorException( throw new ServerErrorException( 'Oops! Update resource error' ) );
        }

        return $resourceUpdated->load('owners', 'category' );
    }

    public function deleteResource($organization, $uid)
    {
        $resource = $this->getResource( $organization, $uid );

        return $this->resourceRepository->delete( $resource->id );
    }

    /**
     * @param $request
     * @return mixed
     */
    protected function getOwners( $request ) {

        $ownerUids = array_map( function( $owner ) {
            return $owner['uid'];
        }, $request->owners );

        return $this->ownerRepository->findWhereIn( 'uid', $ownerUids );
    }

    public function export($category = null, $range = 'all')
    {
        $fileName = 'Resources_'. time(). '.xlsx';

        $data = $this->resourceRepository->with(['category', 'owners'])->all();

        $export = new ResourceExport( $data );

        return Excel::download( $export, $fileName );
    }
}
