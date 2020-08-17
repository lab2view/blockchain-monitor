<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Lab2view\BlockchainMonitor\Address;

class AddressRepository extends BaseRepository
{
    public function __construct(Address $address)
    {
        parent::__construct($address);
    }

    /**
     * @param int $xpub_id
     * @return Address|null
     */
    public function getRandomActiveAddressByXpub(int $xpub_id)
    {
        try {
            $query = $this->model
                ->where('xpub_id', $xpub_id)
                ->where('is_active', true);

            if ($query->whereNull('amount')->exists())
                $query = $query->whereNull('amount');

            return $query->orderBy('index')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

}
