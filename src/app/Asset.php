<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Debit;
use Droplister\XcpCore\App\Issuance;
use Droplister\XcpCore\App\Events\AssetWasCreated;
use Droplister\XcpCore\App\Events\AssetWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'asset_name';

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
        'created' => AssetWasCreated::class,
        'updated' => AssetWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'tx_index',
        'type',
        'asset_name',
        'asset_longname',
        'issuer',
        'owner',
        'description',
        'issuance',
        'divisible',
        'locked',
        'meta',
        'confirmed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
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
    protected $appends = [
        'display_name',
        'issuance_normalized',
    ];

    /**
     * Display Name
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->asset_longname ? $this->asset_longname : $this->asset_name;
    }

    /**
     * Issuance Normalized
     *
     * @return string
     */
    public function getIssuanceNormalizedAttribute()
    {
        return normalizeQuantity($this->issuance, $this->divisible);
    }

    /**
     * Burned
     *
     * @return string
     */
    public function getBurnedAttribute()
    {
        // Burned Quantity
        $burned = $this->balances()->whereHas('addressModel', function ($address) {
            $address->where('burn', '=', 1);
        })->sum('quantity');

        // XCP Gas Fees
        if($this->asset_name === 'XCP')
        {
            $gas_fees = Debit::where('action', 'like', '% fee')->sum('quantity');
            $burned = $burned + $gas_fees;
        }

        return $burned;
    }

    /**
     * Burned Normalized
     *
     * @return string
     */
    public function getBurnedNormalizedAttribute()
    {
        return normalizeQuantity($this->burned, $this->divisible);
    }

    /**
     * Supply
     *
     * @return string
     */
    public function getSupplyAttribute()
    {
        return $this->issuance - $this->burned;
    }

    /**
     * Supply Normalized
     *
     * @return string
     */
    public function getSupplyNormalizedAttribute()
    {
        return normalizeQuantity($this->supply, $this->divisible);
    }

    /**
     * Issuer Address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function issuerAddress()
    {
        return $this->belongsTo(Address::class, 'issuer', 'address');
    }

    /**
     * Owner Address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ownerAddress()
    {
        return $this->belongsTo(Address::class, 'owner', 'address');
    }

    /**
     * Balances
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balances()
    {
        return $this->hasMany(Balance::class, 'asset', 'asset_name');
    }

    /**
     * Credits
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function credits()
    {
        return $this->hasMany(Credit::class, 'asset', 'asset_name');
    }

    /**
     * Debits
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debits()
    {
        return $this->hasMany(Debit::class, 'asset', 'asset_name');
    }

    /**
     * Dividends
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dividends()
    {
        return $this->hasMany(Dividend::class, 'asset', 'asset_name');
    }

    /**
     * Issuances
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issuances()
    {
        return $this->hasMany(Issuance::class, 'asset', 'asset_name');
    }

    /**
     * Sends
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sends()
    {
        return $this->hasMany(Send::class, 'asset', 'asset_name');
    }

    /**
     * Transaction
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_index', 'tx_index');
    }

    /**
     * First Or Create Asset
     *
     * @param  \Droplister\XcpCore\App\Issuance  $issuance
     * @return \Droplister\XcpCore\App\Asset
     */
    public static function firstOrCreateAsset(Issuance $issuance)
    {
        return static::firstOrCreate([
            'asset_name' => $issuance->asset,
        ],[
            'type' => getAssetType($issuance),
            'owner' => $issuance->source,
            'issuer' => $issuance->issuer,
            'asset_longname' => $issuance->asset_longname,
            'description' => $issuance->description,
            'issuance' => $issuance->quantity,
            'divisible' => $issuance->divisible,
            'locked' => $issuance->locked,
            'block_index' => $issuance->block_index,
            'tx_index' => $issuance->tx_index,
            'confirmed_at' => $issuance->confirmed_at,
        ]);
    }

    /**
     * Update Asset
     *
     * @param  \Droplister\XcpCore\App\Issuance  $issuance
     * @return \Droplister\XcpCore\App\Asset
     */
    public static function updateAsset(Issuance $issuance)
    {
        // Get Asset By Name From Issuance
        $asset = static::whereAssetName($issuance->asset)->firstOrFail(); // Should not fail

        // Check If Issuance Is A Creation
        if($asset->tx_index !== $issuance->tx_index)
        {
            // Only Update On Subsequent Issuances
            $asset->update([
                'owner' => $issuance->issuer,
                'description' => $issuance->description,
                'issuance' => $asset->issuance + $issuance->quantity,
                'locked' => ! $asset->locked && $issuance->locked ? 1 : $asset->locked,
            ]);
        }
    }
}