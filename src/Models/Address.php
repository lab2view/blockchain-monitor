<?php

namespace Lab2view\BlockchainMonitor\Models;

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
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection|\Lab2view\BlockchainMonitor\Models\Xpub $xpub
 *
 * @package Lab2view\BlockchainMonitor\Models
 */
class Address extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $casts = [
        'xpub_id' => 'int',
        'index' => 'int',
        'is_active' => 'bool'
    ];

    protected $fillable = [
        'xpub_id',
        'label',
        'index',
        'callback',
        'reference',
        'amount',
        'is_active'
    ];

    public function xpub()
    {
        return $this->belongsTo(\Lab2view\BlockchainMonitor\Models\Xpub::class);
    }
}
