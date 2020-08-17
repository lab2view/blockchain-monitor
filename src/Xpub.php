<?php

namespace Lab2view\BlockchainMonitor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Xpub
 *
 * @property int $id
 * @property string $label
 * @property int $gab
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection|\Lab2view\BlockchainMonitor\Address[] $addresses
 *
 * @package Lab2view\BlockchainMonitor
 */
class Xpub extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $casts = [
        'gab' => 'int'
    ];

    protected $fillable = [
        'label',
        'gab'
    ];

    public function addresses()
    {
        return $this->hasMany(\Lab2view\BlockchainMonitor\Address::class);
    }
}
