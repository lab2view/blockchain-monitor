<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Illuminate\Support\Facades\Log;

abstract class BaseRepository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param string $key
     * @param $value
     * @param bool $withTrashed
     * @return bool
     */
    public function exists(string $key, $value, $withTrashed = false)
    {
        try {
            $query = $this->model->where($key, $value);
            if ($withTrashed)
                $query = $query->withTrashed();

            return $query->exists();
        } catch (\Illuminate\Database\QueryException $exc) {
            Log::error($exc->getMessage(), $exc->getTrace());
            return false;
        }
    }

    /**
     * @param string $attr_name
     * @param $attr_value
     * @param array $relations
     * @param bool $withTrashed
     * @param array $selects
     * @return mixed|null
     */
    public function getByAttribute(string $attr_name, $attr_value, $relations = [], $withTrashed = false, $selects = [])
    {
        try {
            $query = $this->model;
            if (count($relations) > 0)
                $query = $query->with($relations);

            if (count($selects) > 0)
                $query->select($selects);

            if ($withTrashed)
                $query = $query->withTrashed();

            return $query->where($attr_name, $attr_value)->first();
        } catch (\Illuminate\Database\QueryException $exc) {
            Log::error($exc->getMessage(), $exc->getTrace());
            return null;
        }
    }

    /**
     * @param array $inputs
     * @return mixed
     */
    public function store(array $inputs)
    {
        try {
            return $this->model->create($inputs);
        } catch (\Illuminate\Database\QueryException $exc) {
            Log::error($exc->getMessage(), $exc->getTrace());
            return null;
        }
    }

    /**
     * @param $id
     * @param array $relations
     * @param bool $withTrashed
     * @param array $selects
     * @return mixed
     */
    public function getById($id, $relations = [], $withTrashed = false, $selects = [])
    {
        try {
            $query = $this->model;
            if (count($relations) > 0)
                $query = $query->with($relations);

            if (count($selects) > 0)
                $query->select($selects);

            if ($withTrashed)
                $query = $query->withTrashed();

            return $query->find($id);
        } catch (\Illuminate\Database\QueryException $exc) {
            Log::error($exc->getMessage(), $exc->getTrace());
            return null;
        }
    }

    /**
     * @param bool $withTrashed
     * @return mixed
     */
    public function countAll($withTrashed = false)
    {
        $query = $this->model;
        if ($withTrashed)
            $query = $query->withTrashed();

        return $query->count();
    }

    /**
     * @param $id
     * @param array $inputs
     * @return mixed
     */
    public function update($id, array $inputs)
    {
        try {
            $model = $this->getById($id);
            if ($model) {
                $model->update($inputs);
                return $model->fresh();
            } else
                return null;
        } catch (\Illuminate\Database\QueryException $exc) {
            Log::error($exc->getMessage(), $exc->getTrace());
            return null;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        try {
            $data = $this->getById($id);
            return $data ? $data->delete() : false;
        } catch (\Illuminate\Database\QueryException $exc) {
            Log::error($exc->getMessage(), $exc->getTrace());
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function restore($id)
    {
        try {
            $data = $this->getById($id);
            return $data ? $data->restore() : false;
        } catch (\Illuminate\Database\QueryException $exc) {
            Log::error($exc->getMessage(), $exc->getTrace());
            return false;
        }
    }
}
