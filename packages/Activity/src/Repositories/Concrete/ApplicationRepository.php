<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Application;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class ApplicationRepository extends Repository implements ApplicationRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Application::class;
    }

    public function findByUid( $uid, $columns = ['*'] ){
        return $this->findOneByField( 'uid', $uid, $columns );
    }
}
