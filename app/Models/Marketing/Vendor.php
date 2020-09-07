<?php

namespace App\Models\Marketing;

use App\Models\Geo\City;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Vendor
 *
 * @package App\Models\Marketing
 * @property string                $id
 * @property string                $trigger_class
 * @property array                 $name
 * @property array                 $desc
 * @property string                $type
 * @property array                 $default_parameters
 * @property array                 $settings
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property Collection<Condition> $conditions
 * @property Collection<City>      $cities
 */
class Vendor extends Model
{
    use TranslatableTrait, UuidTrait;

    const TYPE_TEMPERATURE = 'temperature';
    const TYPE_WIND = 'wind';
    const TYPE_CLOUDS = 'clouds';
    const TYPE_RAIN = 'rain';
    const TYPE_SNOW = 'snow';
    const TYPE_PRESSURE = 'pressure';
    const TYPE_HUMIDITY = 'humidity';

    protected $table = 'marketing_vendors';

    protected $fillable = [
        'trigger_class',
        'type',
        'default_parameters',
        'settings',
    ];

    protected $translatable = [
        'name',
        'desc',
    ];

    protected $casts = [
        'default_parameters' => 'array',
        'settings'           => 'array',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsToMany
     */
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'marketing_vendors_location', 'vendor_id', 'city_id')
            ->using(VendorLocation::class);
    }

    /**
     * @return HasManyThrough
     */
    public function conditions(): HasManyThrough
    {
        return $this->hasManyThrough(Condition::class, VendorLocation::class, 'vendor_id', 'vendor_location_id');
    }
}
