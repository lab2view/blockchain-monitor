<?php

namespace Lab2view\BlockchainMonitor\Console;

use Illuminate\Console\Command;
use Lab2view\BlockchainMonitor\Repositories\AddressRepository;
use Lab2view\BlockchainMonitor\Repositories\XpubRepository;

class MonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blockchain:monitor {action? : Action to execute} {value? : value if need}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Excute blockchain monitor command action';
    /**
     * @var XpubRepository
     */
    private $xpubRepository;
    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * Create a new command instance.
     *
     * @param XpubRepository $xpubRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(
        XpubRepository $xpubRepository,
        AddressRepository $addressRepository
    )
    {
        parent::__construct();
        $this->xpubRepository = $xpubRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->executeCommand($this->argument('action'), $this->argument('value'));
    }

    private function executeCommand(?string $action, ?string $value)
    {
        switch (mb_strtolower($action)) {
            case 'add_xpub':
                if (is_null($value))
                    $this->error('Please specify the xpub\'s value');
                else {
                    if (!is_null(config('blockchain-monitor.api_key'))) {
                        try {
                            $xpub = $this->xpubRepository->getByAttribute('label', $value, [], true);
                            if ($xpub) {
                                $gab = XpubRepository::getGabByXPub($value);
                                if ($xpub->trashed())
                                    $xpub->restore();
                                $result = $this->xpubRepository->update($xpub->id, ['gab' => $gab]);
                            } else
                                $result = $this->xpubRepository->store([
                                    'label' => $value,
                                    'gab' => 0
                                ]);
                            if ($result)
                                $this->info('New Xpub set successfully !');
                            else
                                $this->warn('There was a problem when saving xpub please verify in log file');
                        } catch (\Exception $e) {
                            $this->error($e->getMessage());
                        }
                    } else
                        $this->error('Please specify the BLOCKCHAIN_API_KEY in your .env');
                }
                break;
        }
    }
}
