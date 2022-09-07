<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Organization\Http\Requests\Org\CreateOrganizationRequest;
use Encoda\Organization\Http\Requests\Org\UpdateOrganizationRequest;
use Encoda\Organization\Models\Industry;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Repositories\IndustryRepository;
use Encoda\Organization\Repositories\OrganizationRepository;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Lcobucci\JWT\Exception;
use Prettus\Validator\Exceptions\ValidatorException;
use Throwable;

class OrganizationService implements OrganizationServiceInterface
{

    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected IndustryRepository $industryRepository,
    )
    {
    }

    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getOrganization( $uid )
    {
        /** @var Organization $organization */
        $organization = $this->organizationRepository->findOneByField('uid', $uid );

        if( !$organization ) {
            throw new NotFoundException( __('org::app.organization.not_found') );
        }

        return $organization->load(['owner','industries']);
    }

    /**
     * @throws ValidatorException|ServerErrorException
     */
    public function createOrganization(CreateOrganizationRequest $request)
    {

        try {
            /** @var Organization $organization */
            $organization = $this->organizationRepository->create( $request->all() );

            // Owner
            $organization->owner()->create( $request->owner );

            // Sync industries
            $industries = $this->getIndustriesFromRequest( $request );

            $organization->industries()->saveMany( $industries );

        }
        catch ( Exception  $e ) {
            Log::error( $e );
            throw new ServerErrorException(__('org::app.organization.create_error'));

        }


        return $organization->load('owner');
    }

    /**
     * @param $request
     * @return Industry[]
     */
    protected function getIndustriesFromRequest($request ) {

        // Industries
        return  array_map( function( $industry ) {
            $industryObj = $this->industryRepository->findByUid( $industry['uid'] );
            if( $industryObj ) {
                return $industryObj;
            }
        }, $request->industries );

    }

    /**
     * @param UpdateOrganizationRequest $request
     * @param $uid
     * @return Organization
     * @throws NotFoundException
     * @throws ValidatorException|ServerErrorException
     */
    public function updateOrganization(UpdateOrganizationRequest $request, $uid)
    {

        try {

            $organization = $this->getOrganization( $uid );

            /** @var Organization $updatedOrganization */
            $updatedOrganization = $this->organizationRepository->update( $request->all(), $organization->id );

            $updatedOrganization->owner()->update( $request->owner );

            //Industries
            // Sync industries
            $industries = $this->getIndustriesFromRequest( $request );
            $updatedOrganization->industries()->detach();
            $updatedOrganization->industries()->saveMany( $industries );

            return $updatedOrganization->load(['owner','industries']);
        }
        catch ( Exception $e ) {
            Log::error( $e );
            throw new ServerErrorException(__('org::app.organization.update_error'));
        }

    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteOrganization($uid)
    {
        $organization = $this->getOrganization( $uid );

        return $this->organizationRepository->delete( $organization->id );
    }

    public function listOrganization()
    {
        return $this->organizationRepository->paginate( config('config.pagination_size') );
    }

}
