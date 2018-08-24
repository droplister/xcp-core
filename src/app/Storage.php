<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\StorageWasCreated;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use CreatesWithLock;
    
    /**
     * Table Name
     *
     * @var string
     */
    protected $table = 'storage';

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
        'created' => StorageWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contract_id',
        'key',
        'value',
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