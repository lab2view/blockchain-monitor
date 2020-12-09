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
     * @param $btc_amount
     * @param string|null $custom_data
     * @return InvoiceResponse
     * @throws BlockchainException
     * @throws QueryException
     */
    public function generateAddress($btc_amount, string $custom_data = null)
    {
        $xpub = XpubRepository::selectInRandomOrder();
        if ($xpub) {
            $address = AddressRepository::getRandomActiveAddressByXpub($xpub->id);
            if ($address)
                return InvoiceRepository::makeInvoice($address, $btc_amount, $custom_data);
            else {
                if ($xpub->gab >= MonitorStatic::getGabLimit())
                    throw QueryException::xpubAllGabLimited();
                else {
                    $address = AddressRepository::generate($xpub);
                    if ($address)
                        return InvoiceRepository::makeInvoice($address, $btc_amount, $custom_data);
                }
                throw QueryException::storeAddressError();
            }
        } else
            throw QueryException::xpubNotFound();
    }

    public function sendBTC($btc_amount, string $address) {

    }

    /**
     * @param $invoice_id
     * @return InvoiceCallback
     * @throws QueryException
     */
    public function getInvoice($invoice_id) {
        try {
            $invoice = InvoiceRepository::getInvoiceCallbackById($invoice_id);
            if ($invoice)
                return new InvoiceCallback($invoice);
            throw QueryException::queryException('Invoice not found');
        } catch (QueryException $e) {
            throw QueryException::queryException($e->getMessage());
        }
    }

    /**
     * @param $amount
     * @param string $symbol
     * @return mixed
     */
    public function convertFromBTC($amount, $symbol = 'USD')
    {
        return MonitorStatic::getRatesInstance()->fromBTC($amount, $symbol);
    }

    /**
     * @param $amount
     * @param string $symbol
     * @return mixed
     */
    public function convertToBTC($amount, $symbol = 'USD')
    {
        return MonitorStatic::getRatesInstance()->toBTC($amount, $symbol);
    }
}
