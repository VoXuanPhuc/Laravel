<?php
namespace Encoda\Core\Providers;

class CoreServiceProvider extends  \Illuminate\Support\ServiceProvider
{

    /**
     *
     */
    public function boot() {
        $this->loadTranslationsFrom( __DIR__ . '/../Resources/lang', 'core' );

        //Provides
        $this->register( EventServiceProvider::class );
    }

}
