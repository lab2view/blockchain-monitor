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
        //
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
                    __DIR__ . '/../database/migrations/create_blockchain_table.php.stub'
                    => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_blockchain_table.php'),
                ], 'migrations');
            }
        }
    }
}
