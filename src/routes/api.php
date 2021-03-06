<?php

use Illuminate\Support\Facades\Route;
use \Lab2view\BlockchainMonitor\Http\Controllers\BlockchainMonitorController;

Route::match(['GET', 'POST'], '/invoice-callback-notify', [BlockchainMonitorController::class, 'callback'])
    ->name('blockchain.notify');
