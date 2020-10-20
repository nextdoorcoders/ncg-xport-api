<?php

namespace App\Models\Trigger;

use App\Models\Geo\Location;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class VendorLocation
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $location_id
 * @property string                $vendor_type_id
 * @property Location              $location
 * @property VendorType            $vendorType
 * @property Collection<Condition> $conditions
 */
class VendorLocation extends Pivot
{
    use HasFactory, UuidTrait;

    protected $table = 'trigger_vendors_locations';

    protected $fillable = [
        'location_id',
        'vendor_type_id',
    ];

    public $timestamps = false;

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendorType(): BelongsTo
    {
        return $this->belongsTo(VendorType::class, 'vendor_type_id');
    }

    /**
     * @return HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'vendor_location_id');
    }
}
