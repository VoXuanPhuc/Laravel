<?php

namespace Encoda\Core\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Core\Helpers\DebugHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('config.enable_database_log')) {
            $this->queryLog();
        }
    }

    protected function queryLog()
    {
        DB::listen(static function($query) {
            File::append(
                storage_path('/logs/query.log'),
                DebugHelper::toSql($query, 'QueryExecuted') . "(" . $query->time . ")" . PHP_EOL
            );
        });
    }
}
