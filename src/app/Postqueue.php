<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\PostqueueWasCreated;

use Illuminate\Database\Eloquent\Model;

class Postqueue extends Model
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $table = 'postqueue';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => PostqueueWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
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