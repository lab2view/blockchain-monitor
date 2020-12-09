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
