<?php

namespace Droplister\XcpCore\App;

use Cache;
use Droplister\XcpCore\App\Events\DispenseWasCreated;

use Illuminate\Database\Eloquent\Model;

class Dispense extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'tx_index';

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
        'created' => DispenseWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tx_index',
        'block_index',
        'tx_hash',
        'dispense_index',
        'source',
        'destination',
        'asset',
        'dispense_quantity',
        'dispenser_tx_hash',
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
     * The attributes that are appended.
     *
     * @var array
     */
    // protected $appends = [
    //     'dispense_quantity_normalized',
    // ];

    /**
     * Dispense Quantity Normalized
     *
     * @return string
     */
    public function getDispenseQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('d_dqn_' . $this->tx_index, function () {
            return normalizeQuantity($this->dispense_quantity, $this->giveAssetModel->divisible);
        });
    }

    /**
     * Give Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function giveAssetModel()
    {
        return $this->belongsTo(Asset::class, 'asset', 'asset_name');
    }
}
