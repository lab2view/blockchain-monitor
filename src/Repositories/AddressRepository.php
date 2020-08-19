<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Illuminate\Support\Str;
use Lab2view\BlockchainMonitor\Address;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;
use Lab2view\BlockchainMonitor\Exceptions\QueryException;
use Lab2view\BlockchainMonitor\MonitorStatic;

class AddressRepository extends BaseRepository
{
    public function __construct(Address $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $xpub_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|Address
     */
    public static function getRandomActiveAddressByXpub(int $xpub_id)
    {
        try {
            $query = Address::query()->where('xpub_id', $xpub_id)
                ->where('is_busy', false);

            if ($query->whereNull('amount')->exists())
                $query = $query->whereNull('amount');

            return $query->inRandomOrder()->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param \Lab2view\BlockchainMonitor\Xpub $xpub
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Address
     * @throws BlockchainException
     * @throws QueryException
     */
    public static function generate(\Lab2view\BlockchainMonitor\Xpub $xpub)
    {
        $reference = Str::lower(Str::random(16));
        $callback = route('blockchain.notify') . "?reference=" . $reference . "&key=" . sha1($reference);
        try {
            $response = MonitorStatic::getReceiveInstance()->generate(MonitorStatic::getApiKey(),
                $xpub->label, $callback, MonitorStatic::getGabLimit());
        } catch (\Exception $e) {
            throw BlockchainException::processException($e->getMessage());
        }
        $address = Address::query()->create([
            'xpub_id' => $xpub->id,
            'label' => $response->getReceiveAddress(),
            'index' => $response->getIndex(),
            'callback' => $response->getCallback(),
            'reference' => $reference
        ]);
        if ($address)
            return $address;
        else
            throw QueryException::storeAddressError();
    }

    /**
     * @param $key
     * @param $reference
     * @return bool
     */
    public static function verifyCallbackKey($key, $reference)
    {
        return $key == sha1($reference);
    }

}
