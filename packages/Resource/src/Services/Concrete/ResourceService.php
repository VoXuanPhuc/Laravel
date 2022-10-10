<?php

namespace Encoda\Resource\Services\Concrete;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Models\Application;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
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
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
     * @return mixed
     * @throws ValidationException
     */
    public function listResource()
    {
        $query = $this->resourceRepository->query()->with('category');
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->resourceRepository->applyPaging($query);
    }


    /**
     * @param $query
     *
     * @throws ValidationException
     */
    public function applySearchFilter($query)
    {
        //Apply filter
        FilterFluent::init()
            ->setTable(Resource::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'description', 'status'])
            ->validate()
            ->applyFilter();
        return $query;
    }

    /**
     * @param $query
     *
     * @return void
     * @throws ValidationException
     */
    public function applySortFilter($query): void
    {
        //Apply sort
        SortFluent::init()
            ->setTable(Resource::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name'])
            ->validate()
            ->applySort();
    }

    /**
     * @param $uid
     * @return Resource
     * @throws NotFoundException
     */
    public function getResource( $uid )
    {
        /** @var Resource $resource */
        $resource = $this->resourceRepository->findByUid( $uid );

        if( !$resource ) {
            throw new NotFoundException( 'Resource not found' );
        }

        return $resource->load('owners', 'category' );
    }

    /**
     * @param CreateResourceRequest $request
     * @return mixed
     * @throws ServerErrorException
     */
    public function createResource( CreateResourceRequest $request )
    {

        $category = $this->categoryService->getResourceCategory( $request->category['uid'] );
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
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function updateResource(UpdateResourceRequest $request, $uid)
    {
        $resource = $this->getResource( $uid );

        $category = $this->categoryService->getResourceCategory( $request->category['uid']);
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
            throw new ServerErrorException( 'Oops! Update resource error' );
        }

        return $resourceUpdated->load('owners', 'category' );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteResource( $uid )
    {
        $resource = $this->getResource( $uid );

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

        return $this->ownerRepository->findByUids( $ownerUids );
    }

    /**
     * @param null $category
     * @param string $range
     * @return BinaryFileResponse
     */
    public function export($category = null, $range = 'all')
    {
        $fileName = 'Resources_'. time(). '.xlsx';

        $data = $this->resourceRepository->with(['category', 'owners'])->all();

        $export = new ResourceExport( $data );

        return Excel::download( $export, $fileName );
    }
}
