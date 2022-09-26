<?php

namespace Encoda\MultiTenancy\Traits;


use Illuminate\Database\Eloquent\Builder;

/**
 * @method creating($callback)
 * @method addGlobalScope($scope, $implementation )
 */
trait MultiTenancyModel
{


    public static function bootMultiTenancyModel() {

        static::creating(function( $model ) {

            if( app()->runningInConsole() && $model->organization_id ) {
                return;
            }
            $model->organization_id = tenant()->id;
        });

        static::addGlobalScope('organization_id', function ( Builder $builder ) {

            if( app()->runningInConsole() ) {
                return;
            }
            return $builder->where('organization_id','=', tenant()->id );
        });
    }
}
