<?php

namespace Encoda\Organization\Http\Controllers\Admin;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Http\Controllers\Controller;
use Encoda\Organization\Http\Requests\Industry\IndustryRequest;
use Encoda\Organization\Repositories\IndustryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

class IndustryController extends Controller
{

    public function __construct( protected IndustryRepository $industryRepository )
    {
    }

    public function index() {
        return $this->industryRepository->all();
    }

    /**
     * @param IndustryRequest $request
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create( IndustryRequest $request ) {
        return $this->industryRepository->create( $request->all() );
    }

    /**
     * @param IndustryRequest $request
     * @param $uid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws NotFoundException
     * @throws ValidatorException
     */
    public function update( IndustryRequest $request, $uid ) {

        $industry = $this->industryRepository->findByUid( $uid );

        if( !$industry ) {
            throw  new NotFoundException('Industry not found');
        }

        return $this->industryRepository->update( $request->all(), $industry->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function delete( $uid ) {

        $industry = $this->industryRepository->findByUid( $uid );

        if( !$industry ) {
            throw  new NotFoundException('Industry not found');
        }

        return $this->industryRepository->delete( $industry->id );
    }
}
