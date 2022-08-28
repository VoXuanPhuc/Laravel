<?php
namespace Encoda\Auth\Providers;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Encoda\Identity\Services\Concrete\UserService;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AuthServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ . '/../Resources/lang', 'auth' );


        //Authentication
        $this->app['auth']->viaRequest('cognito-token', function ( Request $request ) {
            $jwt = $request->bearerToken();

            $userService = $this->app->make(UserServiceInterface::class);

        });
    }

    public function register()
    {
        //Register more providers
        $this->app->register( ServiceBindingProvider::class );

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
