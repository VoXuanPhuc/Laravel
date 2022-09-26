<?php

namespace Encoda\Organization\Validations;

use Encoda\Organization\Repositories\Concrete\OrganizationRepository;

class OrganizationDomainUniqueValidation implements \Illuminate\Contracts\Validation\Rule
{
    protected string $organizationUid = '';
    protected string $domain = '';
    protected OrganizationRepository $organizationRepository;

    public function __construct( $organizationUid = '')
    {
        $this->organizationUid = $organizationUid;
        $this->organizationRepository = app(OrganizationRepository::class);
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes( $attribute, $value ) {

        $this->domain = $this->organizationRepository->generateDomain( $value );

        $query = $this->organizationRepository->where('domain', '=', $this->domain );

        if( strlen( $this->organizationUid ) > 0 ) {
            $query->where('uid', '!=', $this->organizationUid );
        }
        return empty( $query->first() );
    }

    /**
     * @return string
     */
    public function message()
    {
        return "Domain {$this->domain} has been registered, please choose a new one";
    }
}
