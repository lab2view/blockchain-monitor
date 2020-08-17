<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Lab2view\BlockchainMonitor\Address;

class AddressRepository extends BaseRepository
{
    public function __construct(Address $address)
    {
        parent::__construct($address);
    }
}
