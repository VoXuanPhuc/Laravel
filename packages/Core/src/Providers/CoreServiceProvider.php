<?php
namespace Encoda\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends  ServiceProvider
{

    public function boot() {
        $this->loadTranslationsFrom( __DIR__ . '/../Resources/lang', 'core' );

        //Provides
        $this->register( EventServiceProvider::class );
        $this->register( FormRequestProvider::class );

        //API Resource without wrapping
        //JsonResource::withoutWrapping();
    }

}
