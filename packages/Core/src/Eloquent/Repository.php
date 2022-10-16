<?php
namespace Encoda\Core\Eloquent;

use Encoda\Core\Facades\Context;
use Encoda\Core\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Traits\CacheableRepository;

abstract class Repository extends BaseRepository implements RepositoryInterface
{

    /**
     * Find data by field and value
     *
     * @param  string  $field
     * @param  string  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findOneByField($field, $value = null, $columns = ['*'])
    {
        $model = $this->findByField($field, $value, $columns = ['*']);

        return $model->first();
    }

    /**
     * Find data by field and value
     *
     * @param array $where
     * @param array $columns
     * @return mixed
     */
    public function findOneWhere(array $where, $columns = ['*'])
    {
        $model = $this->findWhere($where, $columns);

        return $model->first();
    }

    /**
     * Find data by id
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     * @throws RepositoryException
     */
    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->find($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by id
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     * @throws RepositoryException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findOrFail($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Count results of repository
     *
     * @param array $where
     * @param string $columns
     * @return int
     * @throws RepositoryException
     */
    public function count(array $where = [], $columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($where) {
            $this->applyConditions($where);
        }

        $result = $this->model->count($columns);
        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    /**
     * @param string $columns
     * @return mixed
     * @throws RepositoryException
     */
    public function sum($columns)
    {
        $this->applyCriteria();
        $this->applyScope();

        $sum = $this->model->sum($columns);
        $this->resetModel();

        return $sum;
    }

    /**
     * @param string $columns
     * @return mixed
     * @throws RepositoryException
     */
    public function avg($columns)
    {
        $this->applyCriteria();
        $this->applyScope();

        $avg = $this->model->avg($columns);
        $this->resetModel();

        return $avg;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Delete all
     *
     * @return int
     */
    public function deleteAll()
    {
        $this->model->query()->delete();
    }

    /**
     * Apply paging into models
     *
     * @param      $query
     * @param bool $filters
     *
     * @return mixed
     */
    public function applyPaging($query, bool $forcePaginate = true ,bool $filters = false): mixed
    {
        $options = Context::request()->get();
        if(isset($options['page']) || $forcePaginate)
        {
            $size = $options['page']['size'] ?? config('config.pagination_size');
            $pageNumber = $options['page']['number'] ?? 1;
            $data = $query->paginate($size, $columns = ['*'], $pageName = "page[number]", $pageNumber);

            $data->appends($this->getDataQuery($options,$filters))->links() ;
        }else{
            $data = $query->get();
        }
        return $data;
    }

    /**
     * Get data for extends into url paging
     *
     * @param array $options
     * @param bool  $filters
     *
     * @return array
     */
    public function getDataQuery(array $options, $filters = false)
    {
        $dataQuery = [];
        if(isset($options['page']['size']))
        {
            $dataQuery['page[size]'] =$options['page']['size'];
        }
//        if($filters)
//        {
//            //TODO Add filters for url
//        }
        return $dataQuery;
    }

    /**
     * @param $uid
     * @param string[] $columns
     * @return mixed
     */
    public function findByUid($uid, $columns = ['*'])
    {
        return $this->findOneByField( 'uid', $uid, $columns );
    }

    /**
     * @param $uids
     * @param string[] $columns
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function findByUids( $uids, $columns = ['*'] ) {
        return $this->findWhereIn( 'uid', $uids, $columns );

    }
}
