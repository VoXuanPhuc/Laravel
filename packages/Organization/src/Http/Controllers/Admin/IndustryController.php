<?php

namespace Encoda\Organization\Http\Controllers\Admin;

use Encoda\Organization\Http\Controllers\Controller;
use Encoda\Organization\Http\Requests\Industry\IndustryRequest;
use Encoda\Organization\Services\Interfaces\IndustryServiceInterface;

class IndustryController extends Controller
{

    public function __construct( protected IndustryServiceInterface $industryService )
    {
    }

    public function index() {
        return $this->industryService->listIndustry();
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ) {
        return $this->industryService->getIndustry($uid);
    }

    /**
     * @param IndustryRequest $request
     * @return mixed
     */
    public function create( IndustryRequest $request ) {
        return $this->industryService->createIndustry($request);
    }

    /**
     * @param IndustryRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( IndustryRequest $request, $uid ) {
        return $this->industryService->updateIndustry($request, $uid);
    }

    /**
     * @param $uid
     * @return number
     */
    public function delete( $uid ) {
        return $this->industryService->deleteIndustry($uid);
    }
}
