<?php

namespace Encoda\Organization\Repositories;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Prettus\Validator\Exceptions\ValidatorException;

class OrganizationRepository extends Repository
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

        $organization = parent::create($attributes);

        return $organization->refresh();
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

}
