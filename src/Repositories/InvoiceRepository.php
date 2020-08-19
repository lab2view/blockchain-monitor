<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;
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
    const ERROR = 'ERROR';

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
                'reference' => $address->reference,
                'state' => InvoiceRepository::PENDING]);
            $invoice->save();

            $address->update(['is_busy' => true]);

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
        $invoice->update(['state' => InvoiceRepository::CANCEL, 'reference' => null]);
        XpubRepository::refreshGab($invoice->address->xpub);
    }

    /**
     * @param $value
     * @return float|int
     */
    public static function convertSatoshiAmountToBTC($value)
    {
        return $value / 100000000;
    }

    /**
     * @param $reference
     * @param $transaction_hash
     * @return Invoice|null
     * @throws BlockchainException
     */
    public function getByRefOrHash($reference, $transaction_hash)
    {
        try {
            return $this->model
                ->where('reference', $reference)
                ->orWhere('hash', $transaction_hash)
                ->with(['address'])
                ->first();
        } catch (\Exception $exc) {
            throw BlockchainException::processException($exc->getMessage());
        }
    }
}
