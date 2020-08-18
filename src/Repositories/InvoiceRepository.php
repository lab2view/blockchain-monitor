<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\Invoice;
use Lab2view\BlockchainMonitor\InvoiceResponse;
use Lab2view\BlockchainMonitor\Jobs\AddressMonitorJob;
use Lab2view\BlockchainMonitor\Xpub;

class InvoiceRepository extends BaseRepository
{
    const PENDING = 'PENDING';
    const DONE = 'DONE';
    const WAITING = 'WAITING';
    const READY = 'READY';
    const CANCEL = 'CANCEL';
    const WARNING = 'WARNING';

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
    public static function makeInvoice(\Lab2view\BlockchainMonitor\Address $address, string $btc_amount)
    {
        try {
            $invoice = new Invoice(['address_id' => $address->id,
                'request_amount' => $btc_amount,
                'state' => InvoiceRepository::PENDING]);
            $invoice->save();

            $address->update(['is_active' => false]);

            $cancelDelay = Config::get('blockchain-monitor.cancel_invoice_delay', 5);
            AddressMonitorJob::dispatch($invoice)->delay(now()->addMinutes($cancelDelay));

            return new InvoiceResponse($invoice);
        } catch (\Exception $exception) {
            throw QueryException::storeInvoiceError();
        }
    }

    /**
     * @param Invoice $invoice
     */
    public static function cancelInvoice(Invoice $invoice)
    {
        $invoice->update(['state' => InvoiceRepository::CANCEL]);
        XpubRepository::refreshGab($invoice->address->xpub);
    }
}
