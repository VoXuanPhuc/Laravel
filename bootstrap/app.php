<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

 $app->withFacades();

 $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    \Encoda\Core\Exceptions\ExceptionHandler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');
$app->configure('config');
$app->configure('cognito');
$app->configure('auth');
$app->configure('cors');
$app->configure('permission');
$app->configure('jwt');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

 $app->middleware([
     //Core middleware
     \Encoda\Core\Middlewares\UnsupportedMiddleware::class,
     \Encoda\Core\Middlewares\UnacceptableMiddleware::class,
     \Encoda\Core\Middlewares\JsonPayloadHandlerMiddleware::class,
     \Encoda\Core\Middlewares\ResponseHandlerMiddleware::class,
     \Encoda\CORS\Middlewares\CORSMiddleware::class
 ]);

 $app->routeMiddleware([
     'auth' => Encoda\Auth\Http\Middlewares\AuthMiddleware::class,
     'permission' => \Encoda\Rbac\Middlewares\PermissionMiddleware::class,
     'role'       => \Encoda\Rbac\Middlewares\RoleMiddleware::class,
 ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register( Encoda\Core\Providers\CoreServiceProvider::class );
$app->register( Encoda\Core\Providers\BaseConcordServiceProvider::class );
$app->register( Encoda\Core\Providers\FormRequestProvider::class );
$app->register( Encoda\Auth\Providers\AuthServiceProvider::class );
$app->register( Encoda\Identity\Providers\IdentityServiceProvider::class );
$app->register( Encoda\AWSCognito\Providers\AwsCognitoServiceProvider::class );
$app->register( Encoda\CORS\Providers\CORSServiceProvider::class );
$app->register( Encoda\Rbac\Providers\RbacServiceProvider::class );
$app->register( Encoda\Jwt\Providers\JwtServiceProvider::class );
$app->register( Encoda\Organization\Providers\OrganizationServiceProvider::class );
$app->register( Encoda\EDocs\Providers\DocumentServiceProvider::class );
$app->register( Encoda\Activity\Providers\ActivityServiceProvider::class );
$app->register( \Maatwebsite\Excel\ExcelServiceProvider::class );

$app->alias('cache', \Illuminate\Cache\CacheManager::class);  // if you don't have this already

/**
 * Lang
 */

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
