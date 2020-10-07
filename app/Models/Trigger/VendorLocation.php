<?php

namespace App\Models\Trigger;

use App\Models\Geo\Location;
use App\Models\Traits\UuidTrait;
use App\Models\Vendor\Currency;
use App\Models\Vendor\CurrencyRate;
use App\Models\Vendor\Weather;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class VendorLocation
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $location_id
 * @property string                $vendor_id
 * @property Location              $location
 * @property Vendor                $vendor
 * @property Collection<Condition> $conditions
 * @property Collection<Weather>   $weathers
 * @property Collection<Currency>  $currencies
 */
class VendorLocation extends Pivot
{
    use UuidTrait;

    protected $table = 'trigger_vendors_locations';

    protected $fillable = [
        'location_id',
        'vendor_id',
    ];

    public $timestamps = false;

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'vendor_location_id');
    }

    /**
     * @return HasMany
     */
    public function weathers(): HasMany
    {
        return $this->hasMany(Weather::class, 'vendor_location_id');
    }

    /**
     * @return HasMany
     */
    public function currencies(): HasMany
    {
        return $this->hasMany(CurrencyRate::class, 'vendor_location_id');
    }
}
