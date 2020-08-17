<?php

namespace Lab2view\BlockchainMonitor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Invoice
 *
 * @property string $id
 * @property int $address_id
 * @property int $confirmations
 * @property string $amount
 * @property string $hash
 * @property string $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package Lab2view\BlockchainMonitor
 */
class Invoice extends Model
{
    protected $table = 'blockchain_invoices';

    protected $casts = [
        'confirmations' => 'int',
        'address_id' => 'int'
    ];

    protected $fillable = [
        'address_id',
        'amount',
        'hash',
        'confirmations',
        'state'
    ];

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    public function address()
    {
        return $this->belongsTo(\Lab2view\BlockchainMonitor\Address::class);
    }
}
