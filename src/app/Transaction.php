<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\TransactionWasCreated;
use Droplister\XcpCore\App\Events\TransactionWasUpdated;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'tx_index';

    /**
     * Incrementing?
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TransactionWasCreated::class,
        'updated' => TransactionWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'block_index',
         'message_index',
         'tx_index',
         'tx_hash',
         'type',
         'source',
         'destination',
         'quantity',
         'fee',
         'size',
         'vsize',
         'inputs',
         'outputs',
         'raw',
         'valid',
         'timestamp',
         'confirmed_at',
         'processed_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'confirmed_at',
        'processed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'raw' => 'array',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'fee_normalized',
        'quantity_normalized',
    ];

    /**
     * Get Fee Normalized
     *
     * @return string
     */
    public function getFeeNormalizedAttribute()
    {
        return fromSatoshi($this->fee);
    }

    /**
     * Get Quantity Normalized
     *
     * @return string
     */
    public function getQuantityNormalizedAttribute()
    {
        return fromSatoshi($this->quantity);
    }

    /**
     * Related Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function relatedModel()
    {
        $relation = getModelName($this->type);

        return $this->hasOne($relation, 'tx_hash', 'tx_hash');
    }

    /**
     * Block
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block()
    {
        return $this->belongsTo(Block::class, 'block_index', 'block_index');
    }

    /**
     * Message
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_index', 'message_index');
    }

    /**
     * Processed Txs
     */
    public function scopeProcessed($query)
    {
        return $query->whereNotNull('processed_at');
    }

    /**
     * First or Create Transaction
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return \Droplister\XcpCore\App\Transaction
     */
    public static function firstOrCreateTransaction($message, $bindings)
    {
        return static::firstOrCreate([
            'tx_index' => $bindings['tx_index'],
        ],[
            'message_index' => $message['message_index'],
            'type' => $message['category'],
            'source' => $bindings['source'],
            'tx_hash' => $bindings['tx_hash'],
            'block_index' => $bindings['block_index'],
            'destination' => isset($bindings['destination']) ? $bindings['destination'] : null,
            'valid' => strpos($bindings['status'], 'invalid') === false ? 1 : 0,
            'timestamp' => $message['timestamp'],
            'confirmed_at' => $bindings['confirmed_at'],
        ]);
    }

    /**
     * Update transaction.
     *
     * @param  array  $raw
     * @param  array  $data
     * @return \Droplister\XcpCore\App\Transaction
     */
    public function updateTransaction($raw, $data)
    {
        return $this->update([
            'destination' => $data[1],
            'quantity' => is_null($data[2]) ? 0 : $data[2],
            'fee' => $data[3],
            'size' => $raw['size'],
            'vsize' => $raw['vsize'],
            'inputs' => count($raw['vin']),
            'outputs' => count($raw['vout']),
            'raw' => $raw,
            'processed_at' => Carbon::now(),
        ]);
    }
}
