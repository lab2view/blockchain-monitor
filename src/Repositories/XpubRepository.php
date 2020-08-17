<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\Xpub;

class XpubRepository extends BaseRepository
{
    public static $GAB_LIMIT = (int)config('blockchain-monitor.gap_limit');

    public function __construct(Xpub $xpub)
    {
        parent::__construct($xpub);
    }

    /**
     * @param string $xpub_value
     * @return int
     * @throws \Blockchain\Exception\Error
     * @throws \Blockchain\Exception\HttpError
     */
    public function getGabByXPub(string $xpub_value)
    {
        return $this->blockchain->ReceiveV2->checkAddressGap($this->api_key, $xpub_value);
    }

    /**
     * @return Xpub|null
     */
    public function selectInRandomOrder()
    {
        try {
            $query = $this->model;
            if ($query->where('gab', '<', $this->gab_limit)->exists())
                $query = $query->where('gab', '<', $this->gab_limit);

            $query->inRandomOrder()->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}
