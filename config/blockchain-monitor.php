<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blockchain.info Wallet setting
    |--------------------------------------------------------------------------
    |
    */
    'local_blockchain_server' => 'http://localhost:61548',
    'xpub' => env('BLOCKCHAIN_XPUB'),
    'api_key' => env('BLOCKCHAIN_API_KEY'),
    'wallet_id' => env('BLOCKCHAIN_WALLET_ID'),
    'wallet_password' => env('BLOCKCHAIN_WALLET_PASSWORD'),
    'gap_limit' => 20,

    /*
    |--------------------------------------------------------------------------
    | INVOICE CONFIGURATIONS
    |--------------------------------------------------------------------------
    |
    */
    'confirmations_level' => 3,
    'cancel_invoice_delay' => 5, //Delay is in minute

    /*
    |--------------------------------------------------------------------------
    | CALLBACK route parameter
    |--------------------------------------------------------------------------
    |
    */
    'prefix' => 'api/blockchain-monitor',
    'middleware' => ['api'],
];
