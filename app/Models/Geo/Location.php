<?php

namespace App\Models\Geo;

use App\Models\Account\User;
use App\Models\Traits\NestedTreeTrait;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Vendor;
use App\Models\Trigger\VendorLocation;
use App\Models\Vendor\Weather;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Location
 *
 * @package App\Models\Geo
 * @property string              $id
 * @property string              $type
 * @property string              $parent_id
 * @property integer             $nest_left
 * @property integer             $nest_right
 * @property integer             $nest_depth
 * @property array               $parameters
 * @property array               $name
 * @property Collection<User>    $users
 * @property Collection<Weather> $weathers
 * @property Weather             $actualWeather
 * @property Collection<Vendor>  $vendors
 */
class Location extends Model
{
    use NestedTreeTrait, TranslatableTrait, UuidTrait;

    const TYPE_COUNTRY = 'country';
    const TYPE_STATE = 'state';
    const TYPE_CITY = 'city';

    const TYPES = [
        self::TYPE_COUNTRY,
        self::TYPE_STATE,
        self::TYPE_CITY,
    ];

    protected $table = 'geo_locations';

    protected $fillable = [
        'type',
        'parent_id',
        'nest_left',
        'nest_right',
        'nest_depth',
        'parameters',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $translatable = [
        'name',
    ];

    /*
     * Relations
     */

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'location_id');
    }

    /**
     * @return HasMany
     */
    public function weathers(): HasMany
    {
        return $this->hasMany(Weather::class, 'location_id');
    }

    /**
     * @return HasOne
     */
    public function actualWeather(): HasOne
    {
        return $this->hasOne(Weather::class, 'location_id')
            ->orderBy('datetime_at', 'desc');
    }

    /**
     * @return BelongsToMany
     */
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'trigger_vendors_location', 'location_id', 'vendor_id')
            ->using(VendorLocation::class);
    }
}
