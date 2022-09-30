<?php

namespace Encoda\Excel\Providers;

use Illuminate\Support\ServiceProvider;

class EncodaExcelServiceProvider extends ServiceProvider
{

    public function register()
    {

        $this->mergeConfigFrom( __DIR__ . '/../Config/excel.php', 'excel' );
    }
}
