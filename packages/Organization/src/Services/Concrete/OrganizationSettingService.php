<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Enums\SystemFeatureEnum;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\DTOs\OrganizationSettingDTO;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Services\Interfaces\OrganizationSettingServiceInterface;

class OrganizationSettingService implements OrganizationSettingServiceInterface
{

    /**
     * @throws NotFoundException
     */
    public function buildConfigs(): OrganizationSettingDTO
    {


        /** @var Organization $organization */
        $organization = tenant();

        $organizationDTO = new OrganizationSettingDTO();


        $organizationDTO->name      = $organization->name;
        $organizationDTO->uid       = $organization->uid;
        $organizationDTO->tenantId  = $organization->landlord ? 'default' : $organization->code;
        $organizationDTO->domain    = $organization->domain;
        $organizationDTO->landlord  = $organization->landlord;
        $organizationDTO->logo      = $organization->logo_path;
        $organizationDTO->isActive  = $organization->is_active;
        $organizationDTO->modules   = $this->getTenantAllowedModules( $organization );

        return $organizationDTO;

    }

    /**
     * @param Organization $organization
     * @return array
     */
    protected function getTenantAllowedModules( $organization ): array
    {

        $defaultModules =  [
            SystemFeatureEnum::DASHBOARD,
            SystemFeatureEnum::USER,
            SystemFeatureEnum::SETTING,
            SystemFeatureEnum::NOTIFICATION,
            SystemFeatureEnum::REPORT,
        ];


        if( $organization->landlord ) {
           $landlordModules = [

               SystemFeatureEnum::ORGANIZATION,
               SystemFeatureEnum::INDUSTRY,
           ];

           return array_merge( $defaultModules, $landlordModules );
        }

        $tenantModules = [
            SystemFeatureEnum::DEPARTMENT,
            SystemFeatureEnum::ACTIVITY,
            SystemFeatureEnum::RESOURCE,
            SystemFeatureEnum::SUPPLIER,
            SystemFeatureEnum::DEPENDENCY,
            SystemFeatureEnum::ASSESSMENT,
        ];

        return array_merge( $defaultModules, $tenantModules );
    }

}
