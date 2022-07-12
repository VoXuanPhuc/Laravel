<?php
namespace Encoda\AWSCognito\Providers;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class AwsCognitoServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ . '/../Resources/lang', 'cognito');
    }

    public function register()
    {
        $this->app->singleton( CognitoIdentityProviderClient::class, function( $app ) {

            $configs = [
                'version'     => 'latest',
                'region'      => env('AWS_REGION'),
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY'),
                    'secret' => env('AWS_SECRET_KEY')
                ],
            ];
            return new CognitoIdentityProviderClient( $configs );
        });
    }
}
