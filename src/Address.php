<?php

namespace Lab2view\BlockchainMonitor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 *
 * @property int $id
 * @property int $xpub_id
 * @property string $label
 * @property int $index
 * @property string $callback
 * @property string $reference
 * @property string $amount
 * @property bool $is_busy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @package Lab2view\BlockchainMonitor
 */
class Address extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'blockchain_addresses';

    protected $casts = [
        'xpub_id' => 'int',
        'index' => 'int',
        'is_busy' => 'bool'
    ];

    protected $fillable = [
        'xpub_id',
        'label',
        'index',
        'callback',
        'reference',
        'amount',
        'is_busy'
    ];

    public function xpub()
    {
        return $this->belongsTo(\Lab2view\BlockchainMonitor\Xpub::class, 'xpub_id');
    }

    public function invoices()
    {
        return $this->hasMany(\Lab2view\BlockchainMonitor\Invoice::class, 'address_id');
    }
}
