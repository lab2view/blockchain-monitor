<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\Invoice;
use Lab2view\BlockchainMonitor\InvoiceResponse;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $invoice)
    {
        parent::__construct($invoice);
    }

    /**
     * @param \Lab2view\BlockchainMonitor\Address $address
     * @param string $btc_amount
     * @return InvoiceResponse
     * @throws QueryException
     */
    public function makeInvoice(\Lab2view\BlockchainMonitor\Address $address, string $btc_amount)
    {
        $invoice = $this->store(['address_id' => $address->id, 'request_amount' => $btc_amount]);
        if ($invoice) {
            $address->update(['is_active' => false]);
            return new InvoiceResponse($invoice);
        }
        throw QueryException::storeInvoiceError();
    }
}
