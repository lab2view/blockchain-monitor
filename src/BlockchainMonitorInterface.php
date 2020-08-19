<?php

namespace Lab2view\BlockchainMonitor;

interface BlockchainMonitorInterface {

    public function generateAddress(string $amount, string $custom_data = null);

}
