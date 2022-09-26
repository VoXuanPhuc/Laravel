<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Organization\Http\Requests\Org\CreateOrganizationRequest;
use Encoda\Organization\Http\Requests\Org\UpdateOrganizationRequest;
use Encoda\Organization\Models\Industry;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Repositories\Interfaces\IndustryRepositoryInterface;
use Encoda\Organization\Repositories\Interfaces\OrganizationRepositoryInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;
use Throwable;

class OrganizationService implements OrganizationServiceInterface
{

    public function __construct(
        protected OrganizationRepositoryInterface $organizationRepository,
        protected IndustryRepositoryInterface $industryRepository,
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
        $organization = $this->organizationRepository->findByUid( $uid );

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

            $organization->industries()->sync( $industries );

        }
        catch ( Throwable  $e ) {
            Log::error( $e );
            throw new ServerErrorException(__('org::app.organization.create_error'));

        }


        return $organization->load('owner');
    }


    /**
     * @param UpdateOrganizationRequest $request
     * @param $uid
     * @return Organization
     * @throws ServerErrorException
     */
    public function updateOrganization(UpdateOrganizationRequest $request, $uid)
    {

        try {

            $organization = $this->getOrganization( $uid );

            /** @var Organization $updatedOrganization */
            $updatedOrganization = $this->organizationRepository->update( $request->all(), $organization->id );

            $updatedOrganization->owner()->updateOrCreate(   ['uid' => $request->owner['uid'] ?? '' ], $request->owner );

            //Industries
            // Sync industries
            $industries = $this->getIndustriesFromRequest( $request );
            $updatedOrganization->industries()->sync( $industries );

            return $updatedOrganization->load(['owner','industries']);
        }
        catch ( Throwable $e ) {
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


    /**
     * @param $request
     */
    protected function getIndustriesFromRequest($request ) {

        // Industries
        return $this->industryRepository->findByUids(
            array_map( function( $industry ) {
                return $industry['uid'];
            },$request->industries)
            , 'id'
        );

    }
}
