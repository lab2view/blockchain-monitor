<?php

namespace Lab2view\BlockchainMonitor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Invoice
 *
 * @property string $id
 * @property string $reference
 * @property string $key
 * @property string $address
 * @property string $transaction_hash
 * @property string $value
 * @property int $confirmations
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package Lab2view\BlockchainMonitor
 */
class Callback extends Model
{
    protected $table = 'blockchain_callbacks';

    protected $casts = [
        'confirmations' => 'int'
    ];

    protected $fillable = [
        'reference',
        'key',
        'address',
        'transaction_hash',
        'value',
        'confirmations'
    ];

}
