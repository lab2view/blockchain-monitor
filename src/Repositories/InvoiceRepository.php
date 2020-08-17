<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Lab2view\BlockchainMonitor\Invoice;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $invoice)
    {
        parent::__construct($invoice);
    }
}
