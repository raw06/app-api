<?php

namespace App\Services\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface BaseServiceInterface
{
    /**
     * @param  string  $select
     *
     * @return Builder | boolean
     */
    public function select($select = '*');

    /**
     *
     * @return Collection | boolean
     */
    public function getAll();

    /**
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     *
     * @param $condition
     *
     * @return mixed
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
     * @return mixed
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
     * @param  int  $shopId
     * @param  array  $ids
     * @param  array  $attributes
     *
     * @return int
     */
    public function updateInIds(int $shopId, array $ids, array $attributes);

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
     * @return mixed
     */
    public function updateOrCreate(array $maps, array $attributes);

    /**
     *
     * @param  array  $maps
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function firstOrCreate(array $maps, array $attributes = []);

    /**
     *
     * @param  int  $id
     *
     * @return bool
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
     *
     * @return mixed
     */
    public function getLimitConfig();

    /**
     * @param $plan
     * @param $feature
     * @param $default
     * @return mixed
     */
    public function featureValue($plan, $feature, $default);
}
