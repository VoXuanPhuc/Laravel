<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Rbac\Repositories\Concrete\Database\PermissionGroupRepository;
use Encoda\Rbac\Services\Interfaces\PermissionGroupServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class PermissionGroupController extends Controller
{


    public function __construct(
        protected PermissionGroupServiceInterface $permissionGroupService,
        protected PermissionGroupRepository $permissionGroupRepository,
    )
    {
    }


    public function index() {

        return $this->permissionGroupService->listPermissionGroup();
    }

    /**
     * @throws ValidatorException
     */
    public function create(Request $request ) {
        return $this->permissionGroupRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $id
     * @return LengthAwarePaginator|Collection|mixed
     * @throws RepositoryException
     * @throws ValidatorException
     */
    public function update(Request $request, $id ) {

        $permissionGroup = $this->permissionGroupRepository->findOrFail( $id );
        return $this->permissionGroupRepository->update( $request->all(), $permissionGroup->id );
    }

    /**
     * @throws RepositoryException|NotFoundException
     */
    public function delete($id ) {
        $permissionGroup = $this->permissionGroupRepository->findOrFail( $id );

        if( !$permissionGroup ) {
            throw  new NotFoundException('Permission group not found' );
        }

        $this->permissionGroupRepository->delete( $permissionGroup->id );

        return response()->json('Deleted');
    }


    /**
     * @return mixed
     */
    public function listPermissionByGroup() {
        return $this->permissionGroupService->listPermissionByGroup();
    }
}
