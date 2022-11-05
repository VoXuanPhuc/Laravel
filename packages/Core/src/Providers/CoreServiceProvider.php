<?php

namespace Encoda\Core\Providers;

use DateTime;
use Encoda\Core\AppContext\ContextManager;
use Encoda\Core\AppContext\RequestContext;
use Encoda\Core\Commands\StorageLink;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CoreServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        //Provides
        $this->register(EventServiceProvider::class);
        $this->register(FormRequestProvider::class);

        //API Resource without wrapping
        //JsonResource::withoutWrapping();

        $this->registerValidation();

    }


    /**
     * Register
     */
    public function register()
    {
        $this->app->register(DatabaseServiceProvider::class);

        $this->registerAliases();
        $this->registerSerializer();
        $this->registerCommands();
        $this->registerContextHandler();
    }

    /**
     * Aliases
     */
    public function registerAliases()
    {
        $this->app->alias('core.serializer', Serializer::class);

    }

    /**
     *
     */
    public function registerSerializer()
    {

        $this->app->singleton('core.serializer', function ($app) {

            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];

            return new Serializer($normalizers, $encoders);
        });
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                StorageLink::class,
            ]);
        }
    }

    protected function registerContextHandler()
    {
        $this->app->singleton(RequestContext::class, function () {
            return new RequestContext(request());
        });

        $this->app->singleton('context', function () {
            return new ContextManager([
                'request' => RequestContext::class
            ]);
        });
    }

    /**
     * @return void
     */
    protected function registerValidation()
    {
        Validator::extend('date_utc', function ($attribute, $value, $parameters, $validator) {
            return (bool)DateTime::createFromFormat(DateTime::ATOM, $value);
        });

        Validator::replacer('date_utc', function ($message, $attribute, $rule, $parameters) {
            return __('validation.date_utc', ['attribute' => $attribute]);
        });
    }

}
