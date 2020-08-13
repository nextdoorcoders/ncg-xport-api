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
 * @property string            $id
 * @property string            $alpha2
 * @property string            $alpha3
 * @property array             $name
 * @property string            $phone_mask
 * @property Collection<User>  $users
 * @property Collection<State> $states
 * @property Collection<City>  $cities
 */
class Country extends Model
{
    use TranslatableTrait, UuidTrait;

    protected $table = 'geo_countries';

    protected $fillable = [
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
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    /**
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
