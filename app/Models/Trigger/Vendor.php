<?php

namespace App\Models\Trigger;

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
 * @property array                  $settings
 * @property Carbon                 $created_at
 * @property Carbon                 $updated_at
 * @property Collection<VendorType> $vendorsTypes
 */
class Vendor extends Model
{
    use HasFactory, UuidTrait;

    const TYPE_CALENDAR = 'calendar';
    const TYPE_CURRENCY = 'currency';
    const TYPE_MEDIA_SYNC = 'media_sync';
    const TYPE_WEATHER = 'weather';

    protected $table = 'trigger_vendors';

    protected $fillable = [
        'callback',
        'type',
        'source',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
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
