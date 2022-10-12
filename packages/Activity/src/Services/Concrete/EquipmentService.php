<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\Equipment;
use Encoda\Activity\Repositories\Interfaces\EquipmentRepositoryInterface;
use Encoda\Activity\Services\Interfaces\EquipmentServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EquipmentService implements EquipmentServiceInterface
{

    public function __construct(
        protected EquipmentRepositoryInterface $equipmentRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     * @throws ValidationException
     */
    public function listEquipments()
    {
        $query = $this->equipmentRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->equipmentRepository->applyPaging($query);
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
            ->setTable(Equipment::getTableName())
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
            ->setTable(Equipment::getTableName())
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
    public function getEquipment( $uid )
    {
        $equipment = $this->equipmentRepository->findbyUid($uid);

        if (!$equipment) {
            throw new NotFoundException(__('activity::app.equipment.not_found'));
        }

        return $equipment;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createEquipment( Request $request )
    {
        return $this->equipmentRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateEquipment( Request $request, $uid )
    {
        $equipment = $this->getEquipment( $uid );

        return $this->equipmentRepository->update( $request->all(), $equipment->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteEquipment( $uid )
    {
        $equipment = $this->getEquipment( $uid );

        return $this->equipmentRepository->delete( $equipment->id );
    }
}
