<?php

namespace Lab2view\BlockchainMonitor\Exceptions;

use Exception;

class QueryException extends Exception
{
    public static function xpubNotFound()
    {
        return new static('Xpub not found please use php artisan \'blockchain:monitor add_xpub {value}\' to add xpub' );
    }

    public static function xpubAllGabLimited() {
        return new static('All Xpub gab are excided and there no address in storage. Please add new Xpub.' );
    }
}
