<?php

namespace App\Models\Geo;

use App\Models\Account\User;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Country
 *
 * @package App\Models\Geo
 * @property string       $id
 * @property string       $alpha2
 * @property string       $alpha3
 * @property string       $name
 * @property string       $phone_mask
 * @property Collection   $users
 * @property Collection   $states
 * @property Collection   $cities
 */
class Country extends Model
{
    use TranslatableTrait, UuidTrait;

    protected $table = 'geo_countries';

    protected $fillable = [
        'osm_id',
        'alpha2',
        'alpha3',
        'phone_mask',
    ];

    protected $translatable = [
        'name',
    ];

    /*
     * Relation
     */

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany
     */
    public function states()
    {
        return $this->hasMany(State::class);
    }

    /**
     * @return HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
