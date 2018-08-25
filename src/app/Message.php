<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\MessageWasCreated;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'message_index';

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
        'created' => MessageWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'block_index',
         'message_index',
         'category',
         'command',
         'bindings',
         'timestamp',
         'confirmed_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'confirmed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'bindings' => 'array',
    ];

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
     * Transaction
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'message_index', 'message_index');
    }

    /**
     * First or Create Message
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return \Droplister\XcpCore\App\Asset
     */
    public static function firstOrCreateMessage($message, $bindings)
    {
        return static::firstOrCreate([
            'message_index' => $message['message_index'],
        ],[
            'block_index' => $message['block_index'],
            'command' => $message['command'],
            'category' => isset($message['category']) ? $message['category'] : null,
            'bindings' => $message['bindings'],
            'timestamp' => $message['timestamp'],
            'confirmed_at' => $bindings['confirmed_at'],
        ]);
    }
}