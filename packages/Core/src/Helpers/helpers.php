<?php

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\MultiTenancy\Models\Tenant;
use Encoda\Organization\Models\Organization;
use Illuminate\Support\Facades\Log;

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = ''): string
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if ( ! function_exists('tenant'))
{
    /**
     * Get the configuration path.
     *
     * @return Tenant
     * @throws NotFoundException
     */
    function tenant()
    {

        $tenant = null;

        try {

            /** @var Organization $tenant */
            $tenant = Tenant::current();

            // If we can get tenant from domain then check if is admin
            if( !$tenant || $tenant->landlord ) {

                //Get from header first
                $organizationUid = request()->headers->get('organization');

                if( strlen( $organizationUid ) <= 0 ) {
                    /**
                     *  0: Index
                     *  1: Route defined data: as = 'name', 'users' = 'Controller@action'
                     *  3: Route params
                     */
                    $route = request()->route();

                    $routeParams = $route[2];

                    $organizationUid = $routeParams['organizationUid'] ?? '';
                }

                // If the organization Uid exist on Url then get the organization and set the current tenant to the Org
                if( strlen( $organizationUid ) > 0  ) {
                    /** @var Tenant $tenant */
                    $tenant = Organization::where( 'uid', $organizationUid )->first();


                    if( $tenant ) {
                        $tenant->makeCurrent();
                    }

                }

            }
        }
        catch ( Throwable $e ) {
            Log::error( $e );
            throw new NotFoundException('Tenant not found');
        }

        if( !$tenant ) {
            throw new NotFoundException('Tenant not found');
        }

        return $tenant;
    }
}
