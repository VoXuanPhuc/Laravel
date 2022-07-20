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

    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param null $limit
     * @param array|string[] $columns
     * @param string $method
     * @return mixed|void
     */
    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {

    }

    /**
     * @param array|string[] $columns
     * @return \Illuminate\Database\Eloquent\Collection|mixed|void
     */
    public function all($columns = ['*'])
    {
        return $this->model->list( $columns );
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        $this->model->id = $id;
        return $this->model->delete();
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create( $attributes );
    }
}
