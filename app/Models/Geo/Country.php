<?php

namespace App\Models\Geo;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use UuidTrait;

    protected $table = 'geo_countries';

    /*
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }
}
