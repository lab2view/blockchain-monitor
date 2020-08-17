<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Blockchain\Exception\Error;
use Blockchain\Exception\HttpError;
use Illuminate\Support\Str;
use Lab2view\BlockchainMonitor\Address;
use Lab2view\BlockchainMonitor\Exceptions\BlockchainException;
use Lab2view\BlockchainMonitor\Exceptions\QueryException;

class AddressRepository extends BaseRepository
{
    public function __construct(Address $address)
    {
        parent::__construct($address);
    }

    /**
     * @param int $xpub_id
     * @return Address|null
     */
    public function getRandomActiveAddressByXpub(int $xpub_id)
    {
        try {
            $query = $this->model
                ->where('xpub_id', $xpub_id)
                ->where('is_active', true);

            if ($query->whereNull('amount')->exists())
                $query = $query->whereNull('amount');

            return $query->orderBy('index')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param \Lab2view\BlockchainMonitor\Xpub $xpub
     * @return Address
     * @throws BlockchainException
     * @throws QueryException
     */
    public function generate(\Lab2view\BlockchainMonitor\Xpub $xpub)
    {
        $reference = Str::lower(Str::random(16));
        $callback = route('blockchain.notify') . "?reference=" . $reference . "&key=" . sha1($reference);
        try {
            $response = $this->blockchain->ReceiveV2->generate($this->api_key, $xpub->label, $callback, $this->gab_limit);
        } catch (\Exception $e) {
            throw BlockchainException::processException($e->getMessage());
        }
        $address = $this->store([
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

}
