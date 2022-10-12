<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Models\Application;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ApplicationServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApplicationService implements ApplicationServiceInterface
{

    public function __construct(
        protected ApplicationRepositoryInterface $applicationRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     * @throws ValidationException
     */
    public function listApplications()
    {
        $query = $this->applicationRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->applicationRepository->applyPaging($query);
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
            ->setTable(Application::getTableName())
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
            ->setTable(Activity::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name'])
            ->validate()
            ->applySort();
    }

    /**
     * @param $uid
     * @return Application
     * @throws NotFoundException
     */
    public function getApplication( $uid )
    {
        $application = $this->applicationRepository->findByUid( $uid );

        if( !$application ) {
            throw new NotFoundException(__('activity::app.application.not_found'));
        }

        return $application;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createApplication( Request $request )
    {
        return $this->applicationRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateApplication( Request $request, $uid )
    {
        $application = $this->getApplication( $uid );

        return $this->applicationRepository->update( $request->all(), $application->id );

    }

    /**
     * @throws NotFoundException
     */
    public function deleteApplication( $uid )
    {
        $application = $this->getApplication( $uid );

        return $this->applicationRepository->delete( $application->id );
    }
}
