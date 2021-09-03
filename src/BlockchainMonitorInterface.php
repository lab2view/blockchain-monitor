<?php

namespace Lab2view\BlockchainMonitor;

interface BlockchainMonitorInterface {
    /**
     * @param $amount
     * @param string|null $custom_data
     * @return InvoiceResponse
     */
    public function generateAddress($amount, string $custom_data = null): InvoiceResponse;

    /**
     * @param $invoice_id
     * @return InvoiceCallback
     */
    public function getInvoice($invoice_id): InvoiceCallback;

    /**
     * @param $amount
     * @param string $symbol
     * @return mixed
     */
    public function convertToBTC($amount, string $symbol = 'USD');

    /**
     * @param $amount
     * @param string $symbol
     * @return mixed
     */
    public function convertFromBTC($amount, string $symbol = 'USD');

    /**
     * @param $invoice_id
     * @param $hash
     * @return mixed
     */
    public function verifyInvoiceByHash($invoice_id, $hash);
}
