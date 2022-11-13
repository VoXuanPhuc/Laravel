<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\RecoveryTime;
use Encoda\Activity\Repositories\Interfaces\RecoveryTimeRepositoryInterface;
use Encoda\Activity\Services\Interfaces\RecoveryTimeServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RecoveryTimeService implements RecoveryTimeServiceInterface
{

    public function __construct(
        protected RecoveryTimeRepositoryInterface $recoveryTimeRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     * @throws ValidationException
     */
    public function listRecoveryTimes()
    {
        $query = $this->recoveryTimeRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->recoveryTimeRepository->applyPaging($query);
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
            ->setTable(RecoveryTime::getTableName())
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
            ->setTable(RecoveryTime::getTableName())
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
    public function getRecoveryTime( $uid )
    {
        $recoveryTime = $this->recoveryTimeRepository->findbyUid($uid);

        if (!$recoveryTime) {
            throw new NotFoundException(__('activity::app.recovery_time.not_found'));
        }

        return $recoveryTime;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createRecoveryTime( Request $request )
    {
        return $this->recoveryTimeRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateRecoveryTime( Request $request, $uid )
    {
        $recoveryTime = $this->getRecoveryTime( $uid );

        return $this->recoveryTimeRepository->update( $request->all(), $recoveryTime->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteRecoveryTime( $uid )
    {
        $recoveryTime = $this->getRecoveryTime( $uid );

        return $this->recoveryTimeRepository->delete( $recoveryTime->id );
    }

    /**
     * @return mixed
     */
    public function listAllRecoveryTimes()
    {
        return $this->recoveryTimeRepository->all();
    }
}
