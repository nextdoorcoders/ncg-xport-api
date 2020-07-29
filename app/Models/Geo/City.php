<?php

namespace App\Models\Geo;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use UuidTrait;

    protected $table = 'geo_cities';

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
