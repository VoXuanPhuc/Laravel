<?php
namespace Encoda\Auth\Providers;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Illuminate\Support\Facades\App;

class AuthServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
    }

    public function register()
    {
        $this->app->singleton( AWSCognitoClient::class, function( $app ) {

            $cognitoIdentityProviderClient = App::make( CognitoIdentityProviderClient::class );
            return new AWSCognitoClient(
                $cognitoIdentityProviderClient,
                env('COGNITO_CLIENT_ID'),
                env('COGNITO_CLIENT_SECRET'),
                env('COGNITO_USER_POOL_ID')
            );
        }) ;
    }
}
