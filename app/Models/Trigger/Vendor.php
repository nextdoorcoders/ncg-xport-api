<?php

namespace App\Models\Trigger;

use App\Models\Geo\Location;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use App\Models\Vendor\CurrencyRate;
use App\Models\Vendor\Weather;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Vendor
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $callback
 * @property string                $vendor_type
 * @property string                $value_type
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
    use HasFactory, TranslatableTrait, UuidTrait;

    const LOCATION_GLOBAL = 'global';
    const LOCATION_LOCAL = 'local';

    const VENDOR_TYPE_WEATHER = 'weather';
    const VENDOR_TYPE_CURRENCY = 'currency';
    const VENDOR_TYPE_CALENDAR = 'calendar';

    const VALUE_TYPE_WEATHER_TEMPERATURE = Weather::TYPE_TEMPERATURE;
    const VALUE_TYPE_WEATHER_WIND = Weather::TYPE_WIND;
    const VALUE_TYPE_WEATHER_CLOUDS = Weather::TYPE_PRESSURE;
    const VALUE_TYPE_WEATHER_RAIN = Weather::TYPE_HUMIDITY;
    const VALUE_TYPE_WEATHER_SNOW = Weather::TYPE_CLOUDS;
    const VALUE_TYPE_WEATHER_PRESSURE = Weather::TYPE_RAIN;
    const VALUE_TYPE_WEATHER_HUMIDITY = Weather::TYPE_SNOW;

    const VALUE_TYPE_CURRENCY_EXCHANGE = CurrencyRate::TYPE_EXCHANGE_RATE;
    const VALUE_TYPE_CURRENCY_NATIONAL = CurrencyRate::TYPE_INTERBANK_RATE;
    const VALUE_TYPE_CURRENCY_INTERBANK = CurrencyRate::TYPE_NATIONAL_RATE;

    const WEATHER_VALUES = [
        self::VALUE_TYPE_WEATHER_TEMPERATURE,
        self::VALUE_TYPE_WEATHER_WIND,
        self::VALUE_TYPE_WEATHER_CLOUDS,
        self::VALUE_TYPE_WEATHER_RAIN,
        self::VALUE_TYPE_WEATHER_SNOW,
        self::VALUE_TYPE_WEATHER_PRESSURE,
        self::VALUE_TYPE_WEATHER_HUMIDITY,
    ];

    const CURRENCY_VALUES = [
        self::VALUE_TYPE_CURRENCY_EXCHANGE,
        self::VALUE_TYPE_CURRENCY_NATIONAL,
        self::VALUE_TYPE_CURRENCY_INTERBANK,
    ];

    protected $table = 'trigger_vendors';

    protected $fillable = [
        'callback',
        'vendor_type',
        'value_type',
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
        return $this->belongsToMany(Location::class, 'trigger_vendors_locations', 'vendor_id', 'location_id')
            ->withPivot([
                'id',
            ])
            ->using(VendorLocation::class);
    }

    /**
     * @return HasMany
     */
    public function vendorsLocations(): HasMany
    {
        return $this->hasMany(VendorLocation::class, 'vendor_id');
    }
}
