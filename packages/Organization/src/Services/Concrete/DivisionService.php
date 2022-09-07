<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Http\Requests\Division\CreateDivisionRequest;
use Encoda\Organization\Http\Requests\Division\UpdateDivisionRequest;
use Encoda\Organization\Models\Division;
use Encoda\Organization\Repositories\DivisionRepository;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

class DivisionService implements DivisionServiceInterface
{

    public function __construct(
        protected OrganizationServiceInterface $organizationService,
        protected DivisionRepository $divisionRepository
    )
    {

    }

    /**
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function listDivision( $organizationUid )
    {
        $organization = $this->organizationService->getOrganization( $organizationUid );

        if( $organization ) {
            return $organization->divisions()->paginate( config('config.pagination_size') );
        }

        return [];
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getDivision($organizationUid, $uid)
    {

        $organization = $this->organizationService->getOrganization( $organizationUid );

        $division = $this->divisionRepository->findOneWhere([
            ['organization_id', '=', $organization->id],
            ['uid', '=', $uid],
        ]);

        if( !$division ) {
            throw new NotFoundException( __('org::app.division.not_found') );
        }

        return $division;
    }

    /**
     * @param CreateDivisionRequest $request
     * @param $organizationUid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function createDivision( CreateDivisionRequest $request, $organizationUid )
    {
        $organization = $this->organizationService->getOrganization( $organizationUid );

        $request->merge([ 'organization_id' => $organization->id]);

        return $this->divisionRepository->create( $request->all() );
    }

    /**
     * @param UpdateDivisionRequest $request
     * @param $organizationUid
     * @param $uid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws NotFoundException
     * @throws ValidatorException
     */
    public function updateDivision(UpdateDivisionRequest $request, $organizationUid, $uid)
    {
        $division = $this->getDivision( $organizationUid, $uid );

        return $this->divisionRepository->update( $request->all(), $division->id );
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteDivision($organizationUid, $uid)
    {
        $division =  $this->getDivision( $organizationUid, $uid );
        return $this->divisionRepository->delete( $division->id );
    }
}
