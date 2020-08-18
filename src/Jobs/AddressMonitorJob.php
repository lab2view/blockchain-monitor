<?php

namespace Lab2view\BlockchainMonitor\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Lab2view\BlockchainMonitor\Invoice;
use Lab2view\BlockchainMonitor\Repositories\InvoiceRepository;

class AddressMonitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->invoice->refresh();
            if (is_null($this->invoice->confirmations)) {
                Log::info('START TRAITEMENT ! ');
                $this->invoice->address()->update(['is_active' => true]);

                InvoiceRepository::cancelInvoice($this->invoice);
            }
        } catch (\Exception $e) {
            Log::error('BLOCKCHAIN-MONITOR JOB ERROR ' . $e->getMessage());
        }
    }
}
