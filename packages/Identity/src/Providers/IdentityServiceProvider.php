<?php
namespace Encoda\Identity\Providers;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Illuminate\Support\Facades\App;

class IdentityServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api-admin.php');

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
    }

    public function register()
    {
        // Other providers
        $this->app->register( EventServiceProvider::class );
        //Repositories binding
        $this->app->register(RepositoriesBindingProvider::class );

        //Service binding
        $this->app->register(ServiceBindingProvider::class );

        //Module service
        $this->app->register(ModuleServiceProvider::class );

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
