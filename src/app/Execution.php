<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\ExecutionWasCreated;
use Droplister\XcpCore\App\Events\ExecutionWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Execution extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ExecutionWasCreated::class,
        'updated' => ExecutionWasUpdated::class,
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
        'gas_price',
        'gas_start',
        'gas_cost',
        'gas_remained',
        'value',
        'data',
        'output',
        'status',
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