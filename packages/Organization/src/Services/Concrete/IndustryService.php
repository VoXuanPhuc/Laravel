<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Http\Requests\Industry\IndustryRequest;
use Encoda\Organization\Models\Industry;
use Encoda\Organization\Repositories\Concrete\IndustryRepository;
use Encoda\Organization\Services\Interfaces\IndustryServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;

class IndustryService implements IndustryServiceInterface
{

    public function __construct(
        protected IndustryRepository $industryRepository
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function listIndustry()
    {
        return $this->applySearchFilter($this->industryRepository->query())->get();
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
            ->setTable(Industry::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'description'])
            ->validate()
            ->applyFilter();
        return $query;
    }


    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getIndustry($uid)
    {
        $industry = $this->industryRepository->findByUid( $uid );

        if( !$industry ) {
            throw new NotFoundException('Industry not found');
        }

        return $industry;
    }

    /**
     * @param IndustryRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function createIndustry(IndustryRequest $request)
    {
        return $this->industryRepository->create( $request->all())->refresh();
    }

    /**
     * @param IndustryRequest $request
     * @param $uid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws NotFoundException
     * @throws ValidatorException
     */
    public function updateIndustry(IndustryRequest $request, $uid)
    {
        $industry = $this->getIndustry($uid);

        return $this->industryRepository->update( $request->all(), $industry->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteIndustry($uid)
    {
        $industry = $this->getIndustry($uid);

        return $this->industryRepository->delete( $industry->id );
    }
}
