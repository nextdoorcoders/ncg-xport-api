<?php

namespace App\Models\Trigger;

use App\Casts\Json;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class VendorType
 *
 * @package App\Models\Trigger
 * @property string                 $id
 * @property string                 $callback
 * @property string                 $type
 * @property string                 $source
 * @property object                 $settings
 * @property Carbon                 $created_at
 * @property Carbon                 $updated_at
 * @property Collection<VendorType> $vendorsTypes
 * @property-read Collection|\App\Models\Trigger\VendorLocation[] $vendorsLocations
 * @property-read int|null $vendors_locations_count
 * @property-read int|null $vendors_types_count
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor whereCallback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vendor extends Model
{
    use HasFactory, UuidTrait;

    const TYPE_CALENDAR = 'calendar';
    const TYPE_CURRENCY = 'currency';
    const TYPE_KEYWORD = 'keyword';
    const TYPE_MEDIA_SYNC = 'media_sync';
    const TYPE_UPTIME_ROBOT = 'uptime_robot';
    const TYPE_WEATHER = 'weather';

    protected $table = 'trigger_vendors';

    protected $fillable = [
        'callback',
        'type',
        'source',
        'settings',
    ];

    protected $casts = [
        'settings' => Json::class,
    ];

    /*
     * Relations
     */

    /**
     * @return HasMany
     */
    public function vendorsTypes(): HasMany
    {
        return $this->hasMany(VendorType::class, 'vendor_id');
    }

    /**
     * @return HasManyThrough
     */
    public function vendorsLocations(): HasManyThrough
    {
        return $this->hasManyThrough(VendorLocation::class, VendorType::class, 'vendor_id', 'vendor_type_id');
    }
}
