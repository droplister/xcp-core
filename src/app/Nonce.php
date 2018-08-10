<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\NonceWasCreated;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'address';

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
        'created' => NonceWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
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