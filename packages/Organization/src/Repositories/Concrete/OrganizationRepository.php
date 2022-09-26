<?php

namespace Encoda\Organization\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Repositories\Interfaces\OrganizationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Prettus\Validator\Exceptions\ValidatorException;

class OrganizationRepository extends Repository implements OrganizationRepositoryInterface
{

    public function model()
    {
        return Organization::class;
    }

    /**
     * @param array $attributes
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        // Generate organization code
        $attributes['code'] = $this->generateOrganizationCode( $attributes );
        $attributes['friendly_url'] = !empty($attributes['friendly_url']) ? $attributes['friendly_url'] : strtolower( $attributes['code'] );


        $attributes['domain'] = $this->generateDomain( $attributes['friendly_url'] );
        $organization = parent::create($attributes);

        return $organization->refresh();
    }

    /**
     * @param array $attributes
     * @param $id
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $attributes, $id)
    {
        $attributes['friendly_url'] = !empty($attributes['friendly_url']) ? $attributes['friendly_url'] : strtolower( $attributes['code'] );

        // Only generate domain for other Org, not Escalate
        if( !$attributes['landlord'] ) {
            $attributes['domain'] = $this->generateDomain( $attributes['friendly_url'] );
        }

        return parent::update($attributes, $id);

    }

    /**
     * @param $attributes
     * @return string
     */
    protected function generateOrganizationCode( $attributes ) {

        $code = preg_replace("/[^A-Za-z0-9 ]/", '', $attributes['name']);
        $code = strtoupper( str_replace( ' ', '_', $code ) );

        while( $this->findOneByField( 'code', $code ) ) {
            $code = $code . '_' . Str::random(2);
        }

        return $code;
    }

    /**
     * @param $friendlyUrl
     * @return string
     */
    public function generateDomain( $friendlyUrl ) {
        return $friendlyUrl . "." . config('config.app_domain' );
    }

    /**
     * @param $uid
     * @param string[] $column
     * @return mixed
     */
    public function findByUid($uid, $column = ['*'])
    {
        return $this->findOneByField( 'uid', $uid, $column );
    }
}
