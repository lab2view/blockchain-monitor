<?php

namespace Lab2view\BlockchainMonitor\Models;

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
 * @property \Illuminate\Database\Eloquent\Collection|\Lab2view\BlockchainMonitor\Models\Address[] $addresses
 *
 * @package Lab2view\BlockchainMonitor\Models
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
        return $this->hasMany(\Lab2view\BlockchainMonitor\Models\Address::class);
    }
}
