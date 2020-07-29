<?php

namespace App\Models\Geo;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use UuidTrait;

    protected $table = 'geo_states';

    /*
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }
}
