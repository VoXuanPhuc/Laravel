<?php

namespace Encoda\Identity\Repositories\Concrete\Cognito;

use Encoda\Core\Eloquent\Repository;
use Encoda\Identity\Models\Cognito\CognitoBaseModel;
use Encoda\Identity\Repositories\Interfaces\BaseRepositoryInterface;
use Encoda\Identity\Repositories\Interfaces\CognitoRepositoryInterface;

/**
 * @property CognitoBaseModel $model
 */
abstract class CognitoIdentityBaseRepository extends Repository implements BaseRepositoryInterface
{
    public $id;


}
