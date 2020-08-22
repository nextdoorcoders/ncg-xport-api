<?php

namespace App\Models\Marketing;

use App\Casts\Encrypt;
use App\Models\Geo\City;
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
 * @package App\Models\Marketing
 * @property string                $id
 * @property string                $trigger_class
 * @property array                 $name
 * @property array                 $desc
 * @property array                 $parameters
 * @property string                $type
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
        'parameters',
        'type',
    ];

    protected $translatable = [
        'name',
        'desc',
    ];

    protected $casts = [
        'parameters' => Encrypt::class,
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'marketing_conditions', 'vendor_id', 'group_id')
            ->using(Condition::class);
    }

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
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'marketing_vendors_has_geo_cities', 'vendor_id', 'city_id');
    }
}
