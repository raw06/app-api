<?php

namespace App\Repositories\Base;

use Eloquent;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

abstract class RepositoryEloquent implements RepositoryInterface
{

    /** @var Eloquent */
    protected $_model;

    /**
     * RepositoryEloquent constructor.
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * @return mixed
     */
    abstract public function getModel();

    /**
     * @throws BindingResolutionException
     */
    public function setModel()
    {
        $this->_model = app()->make($this->getModel());
    }

    /**
     * @param  string  $select
     *
     * @return Builder
     */
    public function select($select = '*')
    {
        return $this->_model->select($select);
    }

    /**
     * @param  array  $maps
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function firstOrCreate(array $maps, array $attributes)
    {
        return $this->_model->firstOrCreate($maps, $attributes);
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->_model->all();
    }

    /**
     * @param  array  $maps
     * @param  array  $attributes
     *
     * @return Eloquent|Model
     */
    public function updateOrCreate(array $maps, array $attributes)
    {
        return $this->_model->updateOrCreate($maps, $attributes);
    }

    /**
     * @param $condition
     *
     * @return Model|object|null
     */
    public function findByCondition($condition)
    {
        return $this->_model->where($condition)->first();
    }

    /**
     * @param  int  $id
     *
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->_model->findOrFail($id);
    }

    /**
     * @param  array  $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    /**
     * @param  array  $attributes
     *
     * @return bool
     */
    public function insert(array $attributes)
    {
        return $this->_model->insert($attributes);
    }

    /**
     * @param  array  $attributes
     *
     * @return int
     */
    public function insertGetId(array $attributes)
    {
        return $this->_model->insertGetId($attributes);
    }

    /**
     * @param  array  $ids
     * @param  array  $attributes
     *
     * @return int
     */
    public function updateInIds(array $ids, array $attributes)
    {
        return $this->_model->whereIn('id', $ids)->update($attributes);
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function deleteInIds(array $ids)
    {
        return $this->_model->whereIn('id', $ids)->delete();
    }

    /**
     * @param  int  $id
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);

            return $result;
        }

        return false;
    }

    /**
     * @param  int  $id
     *
     * @return Collection|Eloquent|Eloquent[]|Model|null
     */
    public function find(int $id)
    {
        return $this->_model->find($id);
    }

    /**
     * @param  int  $id
     *
     * @return bool|null
     * @throws Exception
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            return $result->delete();
        }

        return false;
    }

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param  int  $limit
     * @param  array  $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        return $this->paginate($limit, $columns, "simplePaginate");
    }

    /**
     * @param  int  $limit
     * @param  array  $columns
     * @param  string  $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->_model->{$method}($limit, $columns);

        return $results->appends(app('request')->query());
    }

    public function selectByIds(array $ids): Collection
    {
        return $this->_model->whereIn('id', $ids)->get();
    }
}
