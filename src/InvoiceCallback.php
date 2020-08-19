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
     * @var string
     */
    private $custom_data;

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
        $this->custom_data = $invoice->custom_data;
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
    public function getRequestAmount()
    {
        return $this->request_amount;
    }

    /**
     * @return string|null
     */
    public function getReceiveAmount()
    {
        return $this->receive_amount;
    }

    /**
     * @return string|null
     */
    public function getReceiveAmountInSatoshi()
    {
        return !is_null($this->receive_amount) ? $this->receive_amount * 100000000 : null;
    }

    /**
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return int|null
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    /**
     * @return string|null
     */
    public function getCustomData()
    {
        return $this->custom_data;
    }

    /**
     * @return bool
     */
    public function isVerify(): bool
    {
        return $this->verify;
    }

}
