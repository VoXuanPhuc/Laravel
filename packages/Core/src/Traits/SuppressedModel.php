<?php

namespace Encoda\Core\Traits;

use Encoda\Organization\Models\Organization;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method creating($callback)
 * @method addGlobalScope($scope, $implementation )
 */
trait SuppressedModel
{

    /**
     * Only get data which belong to Organization not suppressed
     */
    public static function bootSuppressedModel() {

        static::addGlobalScope('is_suppressed', function ( Builder $builder ) {
            $organizationTableName = Organization::getTableName();

            return $builder->join(
                $organizationTableName,
                "{$organizationTableName}.id",
                '=',
                "{$this->getTable()}.organization_id"
            )->where( "{$organizationTableName}.is_active" , '=', '1' );
        });
    }
}
