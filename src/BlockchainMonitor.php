<?php

namespace Lab2view\BlockchainMonitor;

use Lab2view\BlockchainMonitor\Repositories\AddressRepository;
use Lab2view\BlockchainMonitor\Repositories\XpubRepository;

class BlockchainMonitor {
    /**
     * @var AddressRepository
     */
    private $addressRepository;
    /**
     * @var XpubRepository
     */
    private $xpubRepository;

    public function __construct(
        AddressRepository $addressRepository,
        XpubRepository $xpubRepository
    )
    {
        $this->addressRepository = $addressRepository;
        $this->xpubRepository = $xpubRepository;
    }


}
