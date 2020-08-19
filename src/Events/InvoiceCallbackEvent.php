<?php

namespace Lab2view\BlockchainMonitor\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lab2view\BlockchainMonitor\InvoiceCallback;

class InvoiceCallbackEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var InvoiceCallback
     */
    private $invoiceCallback;

    /**
     * Create a new event instance.
     *
     * @param InvoiceCallback $invoiceCallback
     */
    public function __construct(InvoiceCallback $invoiceCallback)
    {
        $this->invoiceCallback = $invoiceCallback;
    }

    /**
     * @return InvoiceCallback
     */
    public function getInvoiceCallback(): InvoiceCallback
    {
        return $this->invoiceCallback;
    }
}
