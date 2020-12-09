<?php

namespace Lab2view\BlockchainMonitor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lab2view\BlockchainMonitor\Events\InvoiceCallbackEvent;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;
use Lab2view\BlockchainMonitor\InvoiceCallback;
use Lab2view\BlockchainMonitor\Repositories\AddressRepository;
use Lab2view\BlockchainMonitor\Repositories\CallbackRepository;
use Lab2view\BlockchainMonitor\Repositories\InvoiceRepository;

class BlockchainMonitorController extends Controller
{
    /**
     * @var AddressRepository
     */
    private $addressRepository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;
    /**
     * @var CallbackRepository
     */
    private $callbackRepository;

    /**
     * BlockchainMonitorController constructor.
     * @param AddressRepository $addressRepository
     * @param InvoiceRepository $invoiceRepository
     * @param CallbackRepository $callbackRepository
     */
    public function __construct(
        AddressRepository $addressRepository,
        InvoiceRepository $invoiceRepository,
        CallbackRepository $callbackRepository
    )
    {
        $this->addressRepository = $addressRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->callbackRepository = $callbackRepository;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function callback(Request $request)
    {
        $reference = $request->input('reference');
        $key = $request->input('key');
        $transaction_hash = $request->input('transaction_hash');
        $value = $request->input('value');
        $confirmations = $request->input('confirmations');
        try {
            $response_amount = InvoiceRepository::convertSatoshiAmountToBTC($value);

            $state = $confirmations >= config('blockchain-monitor.confirmations_level')
                ? InvoiceRepository::DONE : InvoiceRepository::WAITING;
            $invoice = $this->invoiceRepository->getByRefOrHash($reference, $transaction_hash);
            if ($invoice) {
                $data = [
                    'confirmations' => $confirmations,
                    'hash' => $transaction_hash,
                    'response_amount' => $response_amount,
                    'state' => $state,
                    'reference' => !is_null($transaction_hash) ? null : $transaction_hash
                ];

                $this->invoiceRepository->update($invoice->id, $data);

                if ($invoice->address->is_busy && $confirmations == 0) {
                    $this->addressRepository->update($invoice->address->id, [
                        'is_busy' => false,
                        'amount' => is_null($invoice->address->amount) ? $response_amount
                            : bcadd($invoice->address->amount, $response_amount)
                    ]);
                }

                if (!AddressRepository::verifyCallbackKey($key, $reference))
                    event(new InvoiceCallbackEvent(new InvoiceCallback($invoice->fresh(), false)));
                else
                    event(new InvoiceCallbackEvent(new InvoiceCallback($invoice->fresh())));
            } else {
                $callback = $this->callbackRepository->storeOrUpdate($transaction_hash, $request->all());
                if (!$callback)
                    Log::alert('BLOCKCHAIN-MONITOR CALLBACK INVOICE NOT FOUND ! ', $request->all());
            }
            if ($state == InvoiceRepository::DONE) {
                return '*ok*';
            }
        } catch (BlockchainException $e) {
            Log::critical('BLOCKCHAIN-MONITOR EXCEPTION ('
                . $e->getMessage() . ' FILE: ' . $e->getFile()
                . ' LINE: ' . $e->getLine() . ')', $request->all());
        }
    }
}
