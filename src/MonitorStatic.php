<?php

namespace Lab2view\BlockchainMonitor;

use Blockchain\Blockchain;
use Illuminate\Support\Facades\Config;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;

class MonitorStatic
{
    /**
     * @return string|null
     */
    public static function getApiKey()
    {
        return Config::get('blockchain-monitor.api_key', null);
    }

    /**
     * @return string|null
     */
    public static function getLocalServer()
    {
        return Config::get('blockchain-monitor.local_blockchain_server', null);
    }

    /**
     * @return string|null
     */
    public static function getWalletId()
    {
        return Config::get('blockchain-monitor.wallet_id', null);
    }

    /**
     * @return string|null
     */
    public static function getGabLimit()
    {
        return Config::get('blockchain-monitor.gap_limit', null);
    }

    public static function getBlockchainInstance()
    {
        return new Blockchain(config('blockchain-monitor.api_key'));
    }

    /**
     * @return \Blockchain\V2\Receive\Receive
     */
    public static function getReceiveInstance()
    {
        $blockchain = MonitorStatic::getBlockchainInstance();
        $blockchain->Wallet->credentials(MonitorStatic::getWalletId(), Config::get('blockchain-monitor.wallet_id'));
        return $blockchain->ReceiveV2;
    }

    /**
     * @return \Blockchain\Wallet\Wallet
     */
    public static function getWalletInstance()
    {
        $blockchain = MonitorStatic::getBlockchainInstance();
        $blockchain->Wallet->credentials(MonitorStatic::getWalletId(), Config::get('blockchain-monitor.wallet_id'));
        return $blockchain->Wallet;
    }
}
