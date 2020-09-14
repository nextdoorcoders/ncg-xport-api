<?php

namespace App\Models\Trigger;

use App\Models\Geo\Location;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Vendor
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $callback
 * @property string                $type
 * @property array                 $default_parameters
 * @property array                 $settings
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property array                 $name
 * @property array                 $desc
 * @property Collection<Condition> $conditions
 * @property Collection<Location>  $locations
 */
class Vendor extends Model
{
    use TranslatableTrait, UuidTrait;

    const TYPE_WEATHER_TEMPERATURE = 'weather_temperature';
    const TYPE_WEATHER_WIND = 'weather_wind';
    const TYPE_WEATHER_CLOUDS = 'weather_clouds';
    const TYPE_WEATHER_RAIN = 'weather_rain';
    const TYPE_WEATHER_SNOW = 'weather_snow';
    const TYPE_WEATHER_PRESSURE = 'weather_pressure';
    const TYPE_WEATHER_HUMIDITY = 'weather_humidity';

    const TYPE_CURRENCY_EXCHANGE = 'currency_exchange';
    const TYPE_CURRENCY_NATIONAL = 'currency_national';
    const TYPE_CURRENCY_INTERBANK = 'currency_interbank';

    const TYPE_CALENDAR = 'calendar';

    protected $table = 'trigger_vendors';

    protected $fillable = [
        'callback',
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
     * @return HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'vendor_id');
    }

    /**
     * @return BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'trigger_vendors_location', 'vendor_id', 'location_id')
            ->using(VendorLocation::class);
    }
}
