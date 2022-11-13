<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\DisruptionScenario;
use Encoda\Activity\Repositories\Interfaces\DisruptionScenarioRepositoryInterface;
use Encoda\Activity\Services\Interfaces\DisruptionScenarioServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DisruptionScenarioService implements DisruptionScenarioServiceInterface
{

    public function __construct(
        protected DisruptionScenarioRepositoryInterface $disruptionScenarioRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     * @throws ValidationException
     */
    public function listDisruptionScenarios()
    {
        $query = $this->disruptionScenarioRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->disruptionScenarioRepository->applyPaging($query);
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
            ->setTable(DisruptionScenario::getTableName())
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
            ->setTable(DisruptionScenario::getTableName())
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
    public function getDisruptionScenario( $uid )
    {
        $disruptionScenario = $this->disruptionScenarioRepository->findbyUid($uid);

        if (!$disruptionScenario) {
            throw new NotFoundException(__('activity::app.disruption_scenario.not_found'));
        }

        return $disruptionScenario;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createDisruptionScenario( Request $request )
    {
        return $this->disruptionScenarioRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateDisruptionScenario( Request $request, $uid )
    {
        $disruptionScenario = $this->getDisruptionScenario( $uid );

        return $this->disruptionScenarioRepository->update( $request->all(), $disruptionScenario->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteDisruptionScenario( $uid )
    {
        $disruptionScenario = $this->getDisruptionScenario( $uid );

        return $this->disruptionScenarioRepository->delete( $disruptionScenario->id );
    }

    /**
     * @return mixed
     */
    public function listAllDisruptionScenarios()
    {
        return $this->disruptionScenarioRepository->all();
    }
}
