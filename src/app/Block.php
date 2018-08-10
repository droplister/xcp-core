<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\BlockWasCreated;
use Droplister\XcpCore\App\Events\BlockWasUpdated;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'block_index';

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
        'created' => BlockWasCreated::class,
        'updated' => BlockWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'block_index',
         'block_hash',
         'ledger_hash',
         'txlist_hash',
         'messages_hash',
         'previous_block_hash',
         'next_block_hash',
         'merkle_root',
         'nonce',
         'chainwork',
         'difficulty',
         'size',
         'stripped_size',
         'weight',
         'tx_count',
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
     * Addresses
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function addresses()
    {
        return $this->hasManyThrough(Address::class, Balance::class, 'block_index', 'address', 'block_index', 'address');
    }

    /**
     * Messages
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'block_index', 'block_index');
    }

    /**
     * Sends
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sends()
    {
        return $this->hasMany(Send::class, 'block_index', 'block_index');
    }

    /**
     * Transactions
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'block_index', 'block_index');
    }

    /**
     * First or Create Block
     *
     * @param  array  $block_data
     * @return \Droplister\XcpCore\App\Block
     */
    public static function firstOrCreateBlock($block_data)
    {
        return static::firstOrCreate([
            'block_index' => $block_data['block_index'],
        ],[
            'block_hash' => $block_data['block_hash'],
            'ledger_hash' => $block_data['ledger_hash'],
            'txlist_hash' => $block_data['txlist_hash'],
            'messages_hash' => $block_data['messages_hash'],
            'previous_block_hash' => $block_data['previous_block_hash'],
            'difficulty' => $block_data['difficulty'],
            'timestamp' => $block_data['block_time'],
            'confirmed_at' => Carbon::createFromTimestamp($block_data['block_time']),
        ]);
    }

    /**
     * Update Block
     *
     * @param  array  $block_data
     * @return \Droplister\XcpCore\App\Block
     */
    public function updateBlock($block_data)
    {
        $this->update([
            'next_block_hash' => isset($block_data['nextblockhash']) ? $block_data['nextblockhash'] : null,
            'merkle_root' => $block_data['merkleroot'],
            'chainwork' => $block_data['chainwork'],
            'nonce' => $block_data['nonce'],
            'size' => $block_data['size'],
            'stripped_size' => $block_data['strippedsize'],
            'weight' => $block_data['weight'],
            'tx_count' => count($block_data['tx']),
            'processed_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}