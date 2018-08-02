<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Mempool extends Model
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $table = 'mempool';

    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'tx_hash';

    /**
     * Incrementing?
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'tx_hash',
         'category',
         'command',
         'bindings',
         'timestamp',
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
     * Transaction
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_hash', 'tx_hash');
    }
}