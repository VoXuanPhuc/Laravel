<?php
namespace Encoda\Auth\Providers;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AuthServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');



        //Authentication
        $this->app['auth']->viaRequest('cognito', function ( Request $request ) {
            $jwt = $request->bearerToken();
        });
    }

    public function register()
    {
        $this->app->singleton( AWSCognitoClient::class, function( $app ) {

            $cognitoIdentityProviderClient = App::make( CognitoIdentityProviderClient::class );

            //TODO: Determine ClientId and ClientScret base on tenant
            return new AWSCognitoClient(
                $cognitoIdentityProviderClient,
                env('COGNITO_CLIENT_ID'),
                env('COGNITO_CLIENT_SECRET'),
                env('COGNITO_USER_POOL_ID')
            );
        }) ;
    }
}
