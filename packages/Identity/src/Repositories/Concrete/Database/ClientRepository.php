<?php

namespace Encoda\Identity\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Identity\Models\Client;

class ClientRepository extends Repository
{

    public function model()
    {
        return Client::class;
    }
}
