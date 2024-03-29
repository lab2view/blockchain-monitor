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
 * @property string $request_amount
 * @property string $response_amount
 * @property string $reference
 * @property string $hash
 * @property string $state
 * @property string $custom_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Address address
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
        'request_amount',
        'response_amount',
        'reference',
        'hash',
        'confirmations',
        'state',
        'custom_data'
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
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
