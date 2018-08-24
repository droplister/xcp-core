<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\RpsMatchExpirationWasCreated;

use Illuminate\Database\Eloquent\Model;

class RpsMatchExpiration extends Model
{
    use CreatesWithLock;
    
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'rps_match_id';

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
        'created' => RpsMatchExpirationWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'block_index',
         'rps_match_id',
         'tx0_address',
         'tx1_address',
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
