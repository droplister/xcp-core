<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\ContractWasCreated;
use Droplister\XcpCore\App\Events\ContractWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'contract_id';

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
        'created' => ContractWasCreated::class,
        'updated' => ContractWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contract_id',
        'block_index',
        'tx_index',
        'tx_hash',
        'source',
        'code',
        'nonce',
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
}
