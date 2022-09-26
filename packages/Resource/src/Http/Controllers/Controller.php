<?php

namespace Encoda\Resource\Http\Controllers;

use Encoda\Organization\Models\Organization;

class Controller extends \Laravel\Lumen\Routing\Controller
{

    /**
     * @return Organization
     * @throws \Encoda\Core\Exceptions\ServerErrorException
     */
    protected function getTenant() {
        return tenant();
    }
}
