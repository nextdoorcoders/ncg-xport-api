<?php

namespace App\Models\Marketing;

use App\Models\Geo\City;
use App\Models\Traits\TranslateTrait;
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
 * @property string                $type
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property Collection<Condition> $conditions
 * @property Collection<City>      $cities
 */
class Vendor extends Model
{
    use TranslateTrait, UuidTrait;

    const TYPE_WEATHER = 'weather';

    protected $table = 'marketing_vendors';

    protected $fillable = [
        'type',
    ];

    protected $translatable = [
        'name',
        'desc',
    ];

    /*
     * Relations
     */

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
