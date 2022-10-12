<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Activity\Models\Utility;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Activity\Services\Interfaces\UtilityServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UtilityService implements UtilityServiceInterface
{

    public function __construct(
        protected UtilityRepositoryInterface $utilityRepository,
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listUtilities()
    {
        $query = $this->utilityRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->utilityRepository->applyPaging($query);
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
            ->setTable(Utility::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'description'])
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
            ->setTable(Utility::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name'])
            ->validate()
            ->applySort();
    }


    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getUtility( $uid )
    {
        $Utility = $this->utilityRepository->findbyUid($uid);

        if (!$Utility) {
            throw new NotFoundException(__('activity::app.Utility.not_found'));
        }

        return $Utility;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createUtility( Request $request )
    {
        return $this->utilityRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateUtility(Request $request, $uid)
    {
        $utility = $this->getUtility( $uid );

        return $this->utilityRepository->update( $request->all(), $utility->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteUtility( $uid )
    {
        $utility = $this->getUtility( $uid );

        return $this->utilityRepository->delete( $utility->id );
    }
}
