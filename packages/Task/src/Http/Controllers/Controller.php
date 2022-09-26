<?php

namespace Encoda\Task\Http\Controllers;

use Encoda\Organization\Models\Organization;

class Controller extends \Laravel\Lumen\Routing\Controller
{


    /**
     * @return Organization
     */
    protected function getTenant() {
        return Organization::find(1);
    }
}
