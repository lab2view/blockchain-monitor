<?php

namespace Lab2view\BlockchainMonitor;

class InvoiceResponse
{
    /**
     * @var string
     */
    private $amount;
    /**
     * @var int
     */
    private $confirmations;
    /**
     * @var string
     */
    private $invoice_id;
    /**
     * @var string
     */
    private $address;

    public function __construct($invoice)
    {
        $this->address = (string)$invoice->address->label;
        $this->amount = $invoice->request_amount;
        $this->confirmations = $invoice->confirmations;
        $this->invoice_id = $invoice->id;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int|null
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    /**
     * @return string
     */
    public function getInvoiceId(): string
    {
        return $this->invoice_id;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }
}
