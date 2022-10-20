<?php

namespace Encoda\Activity\Services\Concrete;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 *
 */
trait ActivityServiceTrait
{


    /**
     * @param array $roles
     * @return mixed
     */
    private function getRoles(array $roles)
    {

        return $this->roleRepository->findByUids(
            array_map(function ($role){
                return $role['uid'];
            }, $roles),
            'id');
    }

    /**
     * @param array $alternativeRoles
     * @return LengthAwarePaginator|Collection|mixed
     */
    private function getAlternativeRoles(array $alternativeRoles)
    {

        return $this->alternativeRoleRepository->findByUids(
            array_map(function ($role){
                return $role['uid'];
            }, $alternativeRoles),
            'id');
    }

    /**
     * @param array $utilities
     * @return mixed
     */
    private function getUtilities(array $utilities)
    {
        return $this->utilityRepository->findByUids(
            array_map(function ($u){
                return $u['uid'];
            }, $utilities),
            'id');
    }


    /**
     * @param $assignee
     * @return mixed
     */
    private function getUser( $assignee )
    {
        if( empty( $assignee )  ) {
            return null;
        }

        return $this->userRepository->findByUid(
            $assignee['uid'],
            'id'
        );
    }

}
