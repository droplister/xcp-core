<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\RollbackWasCreated;
use Droplister\XcpCore\App\Events\RollbackWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Rollback extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => RollbackWasCreated::class,
        'updated' => RollbackWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'message_index',
        'processed_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'processed_at',
    ];
}
