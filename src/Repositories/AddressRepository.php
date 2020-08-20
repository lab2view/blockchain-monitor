<?php

namespace Lab2view\BlockchainMonitor\Repositories;

use Illuminate\Support\Facades\Log;
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
        $urlParts = parse_url(route('blockchain.notify') . "?reference=" . $reference . "&key=" . sha1($reference));
        $urlParts['host'] = preg_replace('/^www\./', '', $urlParts['host']);
        $callback = AddressRepository::build_url($urlParts);

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

    private static function build_url(array $parts)
    {
        $scheme = isset($parts['scheme']) ? ($parts['scheme'] . '://') : '';

        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? (':' . $parts['port']) : '';

        $user = $parts['user'] ?? '';
        $pass = isset($parts['pass']) ? (':' . $parts['pass']) : '';
        $pass = ($user || $pass) ? ($pass . '@') : '';

        $path = $parts['path'] ?? '';

        $query = empty($parts['query']) ? '' : ('?' . $parts['query']);

        $fragment = empty($parts['fragment']) ? '' : ('#' . $parts['fragment']);

        return implode('', [$scheme, $user, $pass, $host, $port, $path, $query, $fragment]);
    }
}
