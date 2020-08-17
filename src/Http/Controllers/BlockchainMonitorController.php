<?php

namespace Lab2view\BlockchainMonitor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlockchainMonitorController extends Controller
{
    public function __construct()
    {
    }

    public function callback(Request $request) {
        Log::debug('INITIATE OPERATION LOGS ', $request->all());
    }
}
