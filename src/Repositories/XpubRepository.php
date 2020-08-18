<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Lab2view\BlockchainMonitor\MonitorStatic;
use Lab2view\BlockchainMonitor\Xpub;

class XpubRepository extends BaseRepository
{
    public function __construct(Xpub $xpub)
    {
        parent::__construct($xpub);
    }

    /**
     * @param string $xpub_value
     * @return int
     * @throws \Blockchain\Exception\Error
     * @throws \Blockchain\Exception\HttpError
     */
    public static function getGabByXPub(string $xpub_value)
    {
        return MonitorStatic::getReceiveInstance()->checkAddressGap(MonitorStatic::getApiKey(), $xpub_value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|Xpub
     */
    public static function selectInRandomOrder()
    {
        try {
            $query = Xpub::query();
            if ($query->where('gab', '<', MonitorStatic::getGabLimit())->exists())
                $query = $query->where('gab', '<', MonitorStatic::getGabLimit());

            return $query->inRandomOrder()->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function refreshGab(Xpub $xpub) {
        $xpub->update(['gab' => XpubRepository::getGabByXPub($xpub->label)]);
    }
}
