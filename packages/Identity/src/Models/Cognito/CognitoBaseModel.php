<?php

namespace Encoda\Identity\Models\Cognito;

use Encoda\AWSCognito\Services\CognitoUserService;
use Illuminate\Database\Eloquent\Model;

abstract class CognitoBaseModel extends Model
{


    public abstract function create( $attributes );
    public abstract function find( $id );
    public abstract function list( $columns = ['*']);

    /**
     * @param array|string|string[] $columns
     * @return \Illuminate\Database\Eloquent\Collection|void
     */
    public static function all( $columns = ['*'] ) {
    }


    /**
     * @param null $limit
     * @param string[] $columns
     * @param string $method
     */
    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {

    }


}
