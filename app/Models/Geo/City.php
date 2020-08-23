<?php

namespace App\Models\Geo;

use App\Models\Marketing\Vendor;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class City
 *
 * @package App\Models\Geo
 * @property string              $country_id
 * @property string              $state_id
 * @property integer             $owm_id
 * @property array               $name
 * @property string              $center
 * @property string              $type
 * @property Country             $country
 * @property State               $state
 * @property Weather             $actualWeather
 * @property Collection<Vendor>  $vendors
 * @property Collection<Weather> $weathers
 */
class City extends Model
{
    use TranslatableTrait, UuidTrait;

    const TYPE_CITY = 'city';
    const TYPE_TOWN = 'town';
    const TYPE_VILLAGE = 'village';

    const TYPES = [
        self::TYPE_CITY,
        self::TYPE_TOWN,
        self::TYPE_VILLAGE,
    ];

    protected $table = 'geo_cities';

    protected $fillable = [
        'country_id',
        'state_id',
        'owm_id',
        'center',
        'type',
    ];

    protected $casts = [
        'center' => 'array',
    ];

    protected $translatable = [
        'name',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return HasMany
     */
    public function weathers()
    {
        return $this->hasMany(Weather::class, 'city_id');
    }

    /**
     * @return HasOne
     */
    public function actualWeather()
    {
        return $this->hasOne(Weather::class, 'city_id')
            ->orderBy('datetime_at', 'desc');
    }

    /**
     * @return BelongsToMany
     */
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'marketing_vendors_has_geo_cities', 'city_id', 'vendor_id')
            ->using(new class extends Pivot {
                use UuidTrait;
            });
    }
}
