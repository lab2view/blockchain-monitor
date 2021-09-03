<?php

namespace Lab2view\BlockchainMonitor;

use Blockchain\Exception\ParameterError;
use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;
use Lab2view\BlockchainMonitor\Repositories\AddressRepository;
use Lab2view\BlockchainMonitor\Repositories\CallbackRepository;
use Lab2view\BlockchainMonitor\Repositories\InvoiceRepository;
use Lab2view\BlockchainMonitor\Repositories\XpubRepository;

class BlockchainMonitor implements BlockchainMonitorInterface
{
    /**
     * @param $amount
     * @param string|null $custom_data
     * @return InvoiceResponse
     * @throws BlockchainException
     * @throws QueryException
     */
    public function generateAddress($amount, string $custom_data = null): InvoiceResponse
    {
        $xpub = XpubRepository::selectInRandomOrder();
        if ($xpub) {
            $address = AddressRepository::getRandomActiveAddressByXpub($xpub->id);
            if ($address)
                return InvoiceRepository::makeInvoice($address, $amount, $custom_data);
            else {
                if ($xpub->gab >= MonitorStatic::getGabLimit())
                    throw QueryException::xpubAllGabLimited();
                else {
                    $address = AddressRepository::generate($xpub);
                    if ($address)
                        return InvoiceRepository::makeInvoice($address, $amount, $custom_data);
                }
                throw QueryException::storeAddressError();
            }
        } else
            throw QueryException::xpubNotFound();
    }

    /**
     * @throws ParameterError
     */
    public function sendBTC($amount, string $address): \Blockchain\Wallet\PaymentResponse
    {
        return MonitorStatic::getWalletInstance()->send($address, $amount);
    }

    /**
     * @param $invoice_id
     * @return InvoiceCallback
     * @throws QueryException
     */
    public function getInvoice($invoice_id): InvoiceCallback
    {
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
     * @param $invoice_id
     * @param $hash
     * @return InvoiceCallback
     * @throws QueryException
     * +
     */
    public function verifyInvoiceByHash($invoice_id, $hash): InvoiceCallback
    {
        try {
            $invoice = InvoiceRepository::getInvoiceCallbackById($invoice_id);
            if ($invoice) {
                if ($invoice->state != InvoiceRepository::DONE) {
                    $invoiceCallback = CallbackRepository::getByHash($hash);
                    if ($invoiceCallback) {
                        $data = [
                            'hash' => $invoiceCallback->transaction_hash,
                            'confirmations' => $invoiceCallback->confirmations,
                            'response_amount' => InvoiceRepository::convertSatoshiAmountToBTC($invoiceCallback->value),
                            'state' => $invoiceCallback->confirmations >= config('blockchain-monitor.confirmations_level')
                                ? InvoiceRepository::DONE : InvoiceRepository::WAITING
                        ];
                        if ($invoice->update($data)) {
                            try {
                                $invoiceCallback->delete();
                                return new InvoiceCallback($invoice->refresh());
                            } catch (\Exception $e) {
                                throw QueryException::queryException($e->getMessage());
                            }
                        }
                    }
                }
                return new InvoiceCallback(InvoiceRepository::verifyInvoiceTransaction($invoice, $hash));
            }
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
    public function convertFromBTC($amount, string $symbol = 'USD')
    {
        return MonitorStatic::getRatesInstance()->fromBTC($amount, $symbol);
    }

    /**
     * @param $amount
     * @param string $symbol
     * @return mixed
     */
    public function convertToBTC($amount, string $symbol = 'USD')
    {
        return MonitorStatic::getRatesInstance()->toBTC($amount, $symbol);
    }
}
