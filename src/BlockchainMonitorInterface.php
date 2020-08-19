<?php

namespace Lab2view\BlockchainMonitor;

interface BlockchainMonitorInterface {

    /**
     * @param $amount
     * @param string|null $custom_data
     * @return InvoiceResponse
     */
    public function generateAddress($amount, string $custom_data = null);

    /**
     * @param $invoice_id
     * @return InvoiceCallback
     */
    public function getInvoice($invoice_id);

}
