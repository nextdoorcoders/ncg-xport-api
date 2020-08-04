<?php

namespace App\Models\Geo;

use App\Models\Marketing\Vendor;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class City
 *
 * @package App\Models\Geo
 * @property string             $id
 * @property string             $country_id
 * @property string             $state_id
 * @property array              $name
 * @property string             $type
 * @property Country            $country
 * @property State              $state
 * @property Collection<Vendor> $vendors
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
        'type',
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
     * @return BelongsToMany
     */
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'marketing_vendors_has_geo_cities', 'city_id', 'vendor_id');
    }
}
