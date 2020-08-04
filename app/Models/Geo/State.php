<?php

namespace App\Models\Geo;

use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class State
 *
 * @package App\Models\Geo
 * @property string           $id
 * @property string           $country_id
 * @property string           $region_id
 * @property array            $name
 * @property Country          $country
 * @property Collection<City> $cities
 */
class State extends Model
{
    use TranslatableTrait, UuidTrait;

    protected $table = 'geo_states';

    protected $fillable = [
        'country_id',
        'region_id',
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
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
