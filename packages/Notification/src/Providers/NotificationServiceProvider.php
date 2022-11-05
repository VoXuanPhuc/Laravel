<?php

namespace Encoda\Notification\Providers;

use Encoda\Notification\Console\Commands\SendNotificationCommand;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class NotificationServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Http/Routes/admin-api.php');
        $this->loadRoutesFrom(__DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'dashboard');
        $this->mergeConfigFrom(__DIR__ . '/../Config/notification.php', 'notification');

        $this->mergeConfigFrom(__DIR__ . '/../Config/mail.php', 'mail');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'notification');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SendNotificationCommand::class
            ]);
        }
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->register(ServiceBindingProvider::class);
        $this->app->register(RepositoryBindingProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->registerDispatcher();
    }

    protected function registerDispatcher() {
        $this->app->bind( Dispatcher::class, ChannelManager::class);

    }
}
