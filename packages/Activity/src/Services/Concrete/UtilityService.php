<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Activity\Services\Interfaces\UtilityServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

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
        return $this->utilityRepository->paginate(config('config.pagination_size'));
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
