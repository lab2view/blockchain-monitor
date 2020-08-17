<?php

namespace Lab2view\BlockchainMonitor;

use Blockchain\Blockchain;
use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\Repositories\AddressRepository;
use Lab2view\BlockchainMonitor\Repositories\InvoiceRepository;
use Lab2view\BlockchainMonitor\Repositories\XpubRepository;

class BlockchainMonitor implements BlockchainMonitorInterface
{
    /**
     * @var Blockchain
     */
    public $Blockchain;
    /**
     * @var XpubRepository
     */
    private $xpubRepository;
    /**
     * @var AddressRepository
     */
    private $addressRepository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * BlockchainMonitor constructor.
     * @param XpubRepository $xpubRepository
     * @param AddressRepository $addressRepository
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(
        XpubRepository $xpubRepository,
        AddressRepository $addressRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->Blockchain = new Blockchain(config('blockchain-monitor.api_key'));
        $this->xpubRepository = $xpubRepository;
        $this->addressRepository = $addressRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @param $amount
     * @param $invoice_id
     * @throws QueryException
     */
    public function generateAddress($amount, $invoice_id)
    {
        $xpub = $this->xpubRepository->selectInRandomOrder();
        if ($xpub) {
            $address = $this->addressRepository->getRandomActiveAddressByXpub($xpub->id);
            if ($address) {
                //use existing address
            } else {
                if ($xpub->gab >= XpubRepository::$GAB_LIMIT)
                    throw QueryException::xpubAllGabLimited();
                else {
                    //generate new address
                }
            }
        } else
            throw QueryException::xpubNotFound();
    }
}
