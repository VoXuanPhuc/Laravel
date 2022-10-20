<?php

namespace Encoda\EasyLog\Providers;

use Encoda\EasyLog\Commands\CleanEasyLogCommand;
use Encoda\EasyLog\EasyLogger;
use Encoda\EasyLog\Entities\EasyLogStatus;
use Encoda\EasyLog\Entities\LogBatch;
use Encoda\EasyLog\Resolvers\CauserResolver;
use Illuminate\Database\Eloquent\Model;
use Encoda\EasyLog\Contracts\EasyLog;
use Encoda\EasyLog\Contracts\EasyLog as EasyLogContract;
use Encoda\EasyLog\Exceptions\InvalidConfiguration;
use Encoda\EasyLog\Models\EasyLog as EasyLogModel;
use Encoda\PackageTools\Package;
use Encoda\PackageTools\PackageServiceProvider;

class EasyLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
        ->name('encoda-easylog')
        ->hasConfigFile('easylog')
        ->hasMigrations([
            '2022_07_10_023336_create_api_logs_table',
            '2022_07_10_033336_create_webhook_logs_table',
            '2022_07_10_033339_create_easy_log_table',
        ])
        ->runsMigrations( true )
        ->hasCommand(CleanEasyLogCommand::class);
    }

    /**
     *  Bind package
     */
    public function registeringPackage()
    {
        $this->app->bind(EasyLogger::class);

        $this->app->scoped(LogBatch::class);

        $this->app->scoped(CauserResolver::class);

        $this->app->scoped(EasyLogStatus::class);
    }

    /**
     * @throws InvalidConfiguration
     */
    public static function determineEasyLogModel(): string
    {
        $easyLogModel = config('easylog.easy_log_model') ?? EasyLogModel::class;

        if (! is_a($easyLogModel, EasyLog::class, true)
            || ! is_a($easyLogModel, Model::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($easyLogModel);
        }

        return $easyLogModel;
    }

    /**
     * @throws InvalidConfiguration
     */
    public static function getEasyLogModelInstance(): EasyLogContract
    {
        $easyLgModelClassName = self::determineEasyLogModel();

        return new $easyLgModelClassName();
    }
}
