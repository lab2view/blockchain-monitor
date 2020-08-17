<?php

namespace Lab2view\BlockchainMonitor;

use Illuminate\Support\ServiceProvider;

class BlockchainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/blockchain-monitor.php', 'blockchain-monitor');
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Lab2view\BlockchainMonitor\Console\MonitorCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (!class_exists('CreateBlockchainTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_blockchain_table.php'
                    => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_blockchain_table.php'),
                ], 'migrations');
            }

            $this->publishes([
                __DIR__.'/../config/blockchain-monitor.php' => config_path('blockchain-monitor.php'),
            ], 'config');
        }
    }
}
