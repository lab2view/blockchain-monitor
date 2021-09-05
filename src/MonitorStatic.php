<?php

namespace Lab2view\BlockchainMonitor;

use Blockchain\Blockchain;
use Blockchain\Explorer\Explorer;
use Blockchain\Rates\Rates;
use Blockchain\V2\Receive\Receive;
use Blockchain\Wallet\Wallet;
use Illuminate\Support\Facades\Config;

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

    public static function getBlockchainInstance(): Blockchain
    {
        return new Blockchain(Config::get('blockchain-monitor.api_key'));
    }

    /**
     * @return Receive
     */
    public static function getReceiveInstance(): Receive
    {
        $blockchain = MonitorStatic::getBlockchainInstance();
        $blockchain->setServiceUrl(Config::get('blockchain-monitor.local_blockchain_server'));
        $blockchain->Wallet->credentials(MonitorStatic::getWalletId(), Config::get('blockchain-monitor.wallet_id'));
        return $blockchain->ReceiveV2;
    }

    /**
     * @return Wallet
     */
    public static function getWalletInstance(): Wallet
    {
        $blockchain = MonitorStatic::getBlockchainInstance();
        $blockchain->setServiceUrl(Config::get('blockchain-monitor.local_blockchain_server'));
        $blockchain->Wallet->credentials(MonitorStatic::getWalletId(), Config::get('blockchain-monitor.wallet_id'));
        return $blockchain->Wallet;
    }

    /**
     * @return Rates
     */
    public static function getRatesInstance(): Rates
    {
        $blockchain = MonitorStatic::getBlockchainInstance();
        return $blockchain->Rates;
    }

    /**
     * @return Explorer
     */
    public static function getExplorerInstance(): Explorer
    {
        $blockchain = MonitorStatic::getBlockchainInstance();
        return $blockchain->Explorer;
    }
}
