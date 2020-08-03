<?php

namespace App\Models\Geo;

use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class City
 *
 * @package App\Models\Geo
 * @property string  $id
 * @property string  $country_id
 * @property string  $state_id
 * @property string  $name
 * @property Country $country
 * @property State   $state
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
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
