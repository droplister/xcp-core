<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\SuicideWasCreated;

use Illuminate\Database\Eloquent\Model;

class Suicide extends Model
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
        'created' => SuicideWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contract_id',
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
