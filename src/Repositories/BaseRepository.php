<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Blockchain\Blockchain;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository
{
    protected $model;
    /**
     * @var Blockchain
     */
    protected $blockchain;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $api_key;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $local_server;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $wallet_id;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $wallet_password;

    /**
     * Create a new repository instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->blockchain = new Blockchain(config('blockchain-monitor.api_key'));
        $this->api_key = config('blockchain-monitor.api_key');
        $this->local_server = config('blockchain-monitor.local_blockchain_server');
        $this->wallet_id = config('blockchain-monitor.wallet_id');
        $this->wallet_password = config('blockchain-monitor.wallet_password');
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
            $query = $this->initiateQuery($relations, $withTrashed, $selects);
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
