<?php

namespace App\Models\Trigger;

use App\Models\Geo\Location;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Vendor
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $trigger_class
 * @property string                $type
 * @property array                 $default_parameters
 * @property array                 $settings
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property array                 $name
 * @property array                 $desc
 * @property Collection<Condition> $conditions
 * @property Collection<Location>  $locations
 */
class Vendor extends Model
{
    use TranslatableTrait, UuidTrait;

    const TYPE_TEMPERATURE = 'temperature';
    const TYPE_WIND = 'wind';
    const TYPE_CLOUDS = 'clouds';
    const TYPE_RAIN = 'rain';
    const TYPE_SNOW = 'snow';
    const TYPE_PRESSURE = 'pressure';
    const TYPE_HUMIDITY = 'humidity';

    protected $table = 'trigger_vendors';

    protected $fillable = [
        'trigger_class',
        'type',
        'default_parameters',
        'settings',
    ];

    protected $translatable = [
        'name',
        'desc',
    ];

    protected $casts = [
        'default_parameters' => 'array',
        'settings'           => 'array',
    ];

    /*
     * Relations
     */

    /**
     * @return MorphMany
     */
    public function conditions(): MorphMany
    {
        return $this->morphMany(Condition::class, 'vendor');
    }

    /**
     * @return BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'trigger_vendors_location', 'vendor_id', 'location_id')
            ->using(VendorLocation::class);
    }
}
