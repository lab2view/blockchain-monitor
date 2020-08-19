<?php


namespace Lab2view\BlockchainMonitor;


class InvoiceCallback
{
    /**
     * @var string
     */
    private $invoice_id;
    /**
     * @var string
     */
    private $state;
    /**
     * @var string
     */
    private $request_amount;
    /**
     * @var string
     */
    private $receive_amount;
    /**
     * @var string
     */
    private $hash;
    /**
     * @var int
     */
    private $confirmations;
    /**
     * @var bool
     */
    private $verify;

    /**
     * InvoiceCallback constructor.
     * @param Invoice $invoice
     * @param bool $verify
     */
    public function __construct(Invoice $invoice, bool $verify = true)
    {
        $this->invoice_id = $invoice->id;
        $this->state = $invoice->state;
        $this->request_amount = $invoice->request_amount;
        $this->receive_amount = $invoice->response_amount;
        $this->hash = $invoice->hash;
        $this->confirmations = $invoice->confirmations;
        $this->verify = $verify;
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
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getRequestAmount(): string
    {
        return $this->request_amount;
    }

    /**
     * @return string
     */
    public function getReceiveAmount(): string
    {
        return $this->receive_amount;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getConfirmations(): int
    {
        return $this->confirmations;
    }

}
