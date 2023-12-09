<?php

namespace App\Repositories\Base;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param  int  $id
     *
     * @return Collection|Eloquent|Eloquent[]|Model|null
     */
    public function find(int $id);


    /**
     *
     * @param $condition
     *
     * @return Model|object|null
     */
    public function findByCondition($condition);

    /**
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function findOrFail($id);

    /**
     *
     * @param  array  $attributes
     *
     * @return Model
     */
    public function create(array $attributes);

    /**
     *
     * @param  array  $attributes
     *
     * @return int
     */
    public function insertGetId(array $attributes);

    /**
     *
     * @param  array  $attributes
     *
     * @return bool
     */
    public function insert(array $attributes);

    /**
     *
     * @param  int  $id
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     *
     * @param  array  $ids
     * @param  array  $attributes
     *
     * @return int
     */
    public function updateInIds(array $ids, array $attributes);

    /**
     *
     * @param  array  $ids
     *
     * @return int
     */
    public function deleteInIds(array $ids);

    /**
     *
     * @param  array  $maps
     * @param  array  $attributes
     *
     * @return Eloquent|Model
     */
    public function updateOrCreate(array $maps, array $attributes);

    /**
     *
     * @param  array  $maps
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function firstOrCreate(array $maps, array $attributes);

    /**
     * @param  int  $id
     *
     * @return bool|null
     * @throws Exception
     */
    public function delete($id);

    /**
     *
     * @param  int  $limit
     * @param  array  $columns
     * @param  string  $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate");

    /**
     *
     * @param  int  $limit
     * @param  array  $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*']);

    /**
     * @param $select
     *
     * @return Builder
     */
    public function select($select = '*');

    /**
     * @param array $ids
     * @return Collection
     */
    public function selectByIds(array $ids): Collection;
}
