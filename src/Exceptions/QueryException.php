<?php

namespace Lab2view\BlockchainMonitor\Exceptions;

use Exception;

class QueryException extends Exception
{
    public static function xpubNotFound()
    {
        return new static('Xpub not found please use php artisan \'blockchain:monitor add_xpub {value}\' to add xpub.' );
    }

    public static function xpubAllGabLimited() {
        return new static('All Xpub gab are excided and there no address in storage. Please add new Xpub.' );
    }

    public static function storeAddressError() {
        return new static('There was an error during the saving of the address. More details in laravel log file !' );
    }

    public static function storeInvoiceError() {
        return new static('There was an error during the saving of the invoice. More details in laravel log file !' );
    }

    public static function queryException($message) {
        return new static($message);
    }
}
