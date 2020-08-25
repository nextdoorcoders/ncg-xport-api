<?php

namespace App\Models\Marketing;

use App\Models\Geo\City;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class VendorLocation
 *
 * @package App\Models\Marketing
 * @property string                $id
 * @property string                $city_id
 * @property string                $vendor_id
 * @property City                  $city
 * @property Vendor                $vendor
 * @property Collection<Condition> $condition
 */
class VendorLocation extends Pivot
{
    use UuidTrait;

    protected $table = 'marketing_vendors_location';

    protected $fillable = [
        'city_id',
        'group_id',
    ];

    public $timestamps = false;

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
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
}
