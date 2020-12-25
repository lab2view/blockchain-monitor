<?php


namespace Lab2view\BlockchainMonitor\Repositories;


use Illuminate\Support\Facades\Log;
use Lab2view\BlockchainMonitor\Callback;

class CallbackRepository extends BaseRepository
{
    public function __construct(Callback $invoice)
    {
        parent::__construct($invoice);
    }

    /**
     * @param $hash
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Callback|object|null
     */
    public static function getByHash($hash)
    {
        try {
            return Callback::query()->where('transaction_hash', $hash)->first();
        } catch (\Exception $exc) {
            Log::critical('BLOCKCHAIN-MONITOR GET HASH CALLBACK EXCEPTION ('
                . $exc->getMessage() . ' FILE: ' . $exc->getFile()
                . ' LINE: ' . $exc->getLine() . ')', ['hash' => $hash]);
            return null;
        }
    }

    /**
     * @param string $transaction_hash
     * @param array $data
     * @return Callback|null
     */
    public function storeOrUpdate(string $transaction_hash, array $data)
    {
        try {
            return $this->model->updateOrCreate([
                'transaction_hash' => $transaction_hash
            ], $data);
        } catch (\Exception $exc) {
            Log::critical('BLOCKCHAIN-MONITOR STORE OR UPDATE CALLBACK EXCEPTION ('
                . $exc->getMessage() . ' FILE: ' . $exc->getFile()
                . ' LINE: ' . $exc->getLine() . ')', $data);
            return null;
        }
    }
}
