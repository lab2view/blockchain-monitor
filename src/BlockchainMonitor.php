<?php

namespace Lab2view\BlockchainMonitor;

use Blockchain\Blockchain;
use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;
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
     * @param string $btc_amount
     * @throws QueryException
     * @throws BlockchainException
     */
    public function generateAddress(string $btc_amount)
    {
        $xpub = $this->xpubRepository->selectInRandomOrder();
        if ($xpub) {
            $address = $this->addressRepository->getRandomActiveAddressByXpub($xpub->id);
            if ($address)
                return $this->invoiceRepository->makeInvoice($address, $btc_amount);
            else {
                if ($xpub->gab >= $this->xpubRepository->getGabLimit())
                    throw QueryException::xpubAllGabLimited();
                else {
                    $address = $this->addressRepository->generate($xpub);
                    if ($address)
                        return $this->invoiceRepository->makeInvoice($address, $btc_amount);
                }
                throw QueryException::storeAddressError();
            }
        } else
            throw QueryException::xpubNotFound();
    }
}
