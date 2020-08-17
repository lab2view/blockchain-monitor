<?php

namespace Lab2view\BlockchainMonitor\Exceptions;

use Exception;

class BlockchainException extends Exception
{
    public static function processException($message)
    {
        return new static($message);
    }
}
