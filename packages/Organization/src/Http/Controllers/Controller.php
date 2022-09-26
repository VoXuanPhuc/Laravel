<?php

namespace Encoda\Organization\Http\Controllers;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\MultiTenancy\Models\Tenant;

class Controller extends \Laravel\Lumen\Routing\Controller
{

    public function __construct()
    {

        echo '1';
    }

    /**
     * @return Tenant
     * @throws NotFoundException
     */
    protected function getTenant() {
        return tenant();
    }
}
