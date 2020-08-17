<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Lab2view\BlockchainMonitor\Xpub;

class XpubRepository extends BaseRepository
{
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
}
