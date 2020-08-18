<?php

namespace Lab2view\BlockchainMonitor;

use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;
use Lab2view\BlockchainMonitor\Repositories\AddressRepository;
use Lab2view\BlockchainMonitor\Repositories\InvoiceRepository;
use Lab2view\BlockchainMonitor\Repositories\XpubRepository;

class BlockchainMonitor implements BlockchainMonitorInterface
{
    /**
     * @param string $btc_amount
     * @throws QueryException
     * @throws BlockchainException
     */
    public function generateAddress(string $btc_amount)
    {
        $xpub = XpubRepository::selectInRandomOrder();
        if ($xpub) {
            $address = AddressRepository::getRandomActiveAddressByXpub($xpub->id);
            if ($address)
                return InvoiceRepository::makeInvoice($address, $btc_amount);
            else {
                if ($xpub->gab >= MonitorStatic::getGabLimit())
                    throw QueryException::xpubAllGabLimited();
                else {
                    $address = AddressRepository::generate($xpub);
                    if ($address)
                        return InvoiceRepository::makeInvoice($address, $btc_amount);
                }
                throw QueryException::storeAddressError();
            }
        } else
            throw QueryException::xpubNotFound();
    }
}
