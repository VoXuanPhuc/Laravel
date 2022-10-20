<?php
namespace Encoda\AWSCognito\Providers;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Encoda\AWSCognito\Guards\CognitoGuard;
use Encoda\AWSCognito\Services\CognitoJWT;
use Encoda\AWSCognito\Services\CognitoJWTManager;
use Encoda\AWSCognito\Services\CognitoJWTVerifier;
use Encoda\AWSCognito\Services\CognitoTokenParser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AwsCognitoServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ . '/../Resources/lang', 'cognito');
        $this->mergeConfigFrom( __DIR__ . '/../Config/cognito.php', 'cognito' );
    }

    public function register()
    {

        $this->registerAliases();

        // This one need to be placed before $this->registerCognitoClient
        $this->registerCognitoIdentityClient();

        $this->registerCognitoClient();

        $this->registerBlackListProvider();

        $this->registerJWTVerifier();

        $this->registerTokenParser();

        $this->registerJWTManager();

        $this->registerJWTProvider();

        // This need to be placed before extend auth guard
        $this->registerUserProvider();

        $this->extendAuthGuard();
    }

    /**
     * Extend auth guard
     */
    protected function extendAuthGuard() {

        //Authentication
        $this->app['auth']->extend('cognito', function( $app, $name, array $config ){

            return new CognitoGuard(
                $this->app['cognito.jwt'],
                $this->app['auth']->createUserProvider( $config['provider'] ),
                $this->app['request']
            );
        });



    }

    /**
     * Aliases
     */
    protected function registerAliases() {

        $this->app->alias('cognito.identity_client', CognitoIdentityProviderClient::class );
        $this->app->alias('cognito.client', AWSCognitoClient::class );
        $this->app->alias('cognito.jwt', CognitoJWT::class );
        $this->app->alias('cognito.manager', CognitoJWTManager::class );
        $this->app->alias('cognito.blacklist', TokenBlacklistProvider::class );
        $this->app->alias('cognito.parser', CognitoTokenParser::class );
        $this->app->alias('cognito.verifier', CognitoJWTVerifier::class );
    }

    /**
     * Black list provider
     */
    protected function registerBlackListProvider() {
        $this->app->singleton( 'cognito.blacklist', function( $app ) {
            return new TokenBlacklistProvider();
        });

    }

    /**
     * Verifier
     */
    protected function registerJWTVerifier() {
        $this->app->singleton( 'cognito.verifier', function( $app ) {
            return new CognitoJWTVerifier();
        });

    }

    /**
     * Cognito Client
     */
    protected function registerCognitoIdentityClient() {

        //Cognito client
        $this->app->singleton( 'cognito.identity_client', function( $app ) {

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

    /**
     * Register JwtProvier
     */
    protected function registerJWTProvider() {
        $this->app->singleton( 'cognito.jwt', function( $app ) {
            return new CognitoJWT(
                $this->app['cognito.manager']
            );
        });

    }
    /**
     * Register JwtManager
     */
    protected function registerJWTManager() {
        $this->app->singleton( 'cognito.manager', function( $app ) {
            return new CognitoJWTManager();
        });

    }

    /**
     * Register user provider
     */
    protected function registerUserProvider() {
        Auth::provider('cognito', function( $app, array $config ) {
            return new CognitoUserProvider();
        });

    }
    /**
     * Register user provider
     */
    protected function registerTokenParser() {
        $this->app->singleton( 'cognito.parser', function( $app ) {
            return new CognitoTokenParser();
        });

    }

    /**
     * Client
     */
    protected function registerCognitoClient() {

        $this->app->singleton( 'cognito.client', function( $app ) {

            return new AWSCognitoClient(
                $app['cognito.identity_client'],
                config('cognito.client_id'),
                config('cognito.client_secret'),
                config('cognito.user_pool_id'),
            );
        }) ;

    }
}
