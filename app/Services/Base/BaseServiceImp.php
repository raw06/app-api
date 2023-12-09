<?php

namespace App\Services\Base;

use App\Repositories\Base\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

abstract class BaseServiceImp implements BaseServiceInterface
{
    /** @var RepositoryInterface */
    protected $_repository;

    /**
     *
     * @param  string  $select
     *
     * @return Builder | boolean
     */
    public function select($select = '*')
    {
        try {
            return $this->_repository->select($select);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     *
     * @param  array  $maps
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function firstOrCreate(array $maps, array $attributes = [])
    {
        try {
            return $this->_repository->firstOrCreate($maps, $attributes);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");
            return false;
        }
    }

    /**
     * @return Collection | boolean
     */
    public function getAll()
    {
        try {
            return $this->_repository->getAll();
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  array  $maps
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function updateOrCreate(array $maps, array $attributes)
    {
        try {
            return $this->_repository->updateOrCreate($maps, $attributes);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  int  $id
     *
     * @return mixed
     */
    public function find($id)
    {
        try {
            return $this->_repository->find($id);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param $condition
     *
     * @return mixed
     */
    public function findByCondition($condition)
    {
        try {
            return $this->_repository->findByCondition($condition);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  int  $id
     *
     * @return mixed
     */
    public function findOrFail($id)
    {
        try {
            return $this->_repository->findOrFail($id);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        try {
            return $this->_repository->create($attributes);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  array  $attributes
     *
     * @return bool
     */
    public function insert(array $attributes)
    {
        try {
            return $this->_repository->insert($attributes);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  array  $attributes
     *
     * @return int
     */
    public function insertGetId(array $attributes)
    {
        try {
            return $this->_repository->insertGetId($attributes);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  int  $shopId
     * @param  array  $ids
     * @param  array  $attributes
     *
     * @return int
     */
    public function updateInIds(int $shopId, array $ids, array $attributes)
    {
        try {
            return $this->_repository->updateInIds($ids, $attributes);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     *
     * @param  array  $ids
     *
     * @return int
     */
    public function deleteInIds(array $ids)
    {
        try {
            return $this->_repository->deleteInIds($ids);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  int  $id
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        try {
            return $this->_repository->update($id, $attributes);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id)
    {
        try {
            return $this->_repository->delete($id);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * Retrieve all data of _repository, simple paginated
     *
     * @param  int  $limit
     * @param  array  $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        try {
            return $this->paginate($limit, $columns, 'simplePaginate');
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }

    /**
     * @param  int  $limit
     * @param  array  $columns
     * @param  string  $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {
        try {
            return $this->_repository->paginate($limit, $columns, $method);
        } catch (Throwable $e) {
            logger()->error("{$e->getMessage()} {$e->getTraceAsString()}");

            return false;
        }
    }


    /**
     *
     * @return mixed
     */
    public function getLimitConfig()
    {
        return config('secom.review.limit', 10);
    }

    /**
     * @inheritDoc
     */
    public function featureValue($plan, $feature, $default)
    {
        $feature = $plan->features->first(function ($value) use ($feature) {
            return $value->code === $feature;
        });

        if (is_null($feature)) {
            return $default;
        }

        return $feature->value;
    }
}
