<?php
namespace Encoda\Core\Providers;

use Encoda\Core\AppContext\ContextManager;
use Encoda\Core\AppContext\RequestContext;
use Encoda\Core\Commands\StorageLink;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CoreServiceProvider extends  ServiceProvider
{

    public function boot() {
        $this->loadTranslationsFrom( __DIR__ . '/../Resources/lang', 'core' );

        //Provides
        $this->register( EventServiceProvider::class );
        $this->register( FormRequestProvider::class );

        //API Resource without wrapping
        //JsonResource::withoutWrapping();

        $this->app->singleton(Serializer::class, function( $app ) {

            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];

            return new Serializer($normalizers, $encoders);
        });
    }


    /**
     * Register
     */
    public function register()
    {
        $this->app->register(DatabaseServiceProvider::class);
        $this->registerCommands();
        $this->registerContextHandler();
    }

    /**
     * @return void
     */
    protected function registerCommands() {
        if ( $this->app->runningInConsole() ) {
            $this->commands([
                StorageLink::class,
            ]);
        }
    }

    protected function registerContextHandler(){
        $this->app->singleton(RequestContext::class, function () {
            return new RequestContext(request());
        });

        $this->app->singleton('context', function () {
            return new ContextManager([
                'request' => RequestContext::class
            ]);
        });
    }

}
