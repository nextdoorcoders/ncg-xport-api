<?php

namespace App\Models\Trigger;

use App\Models\Geo\Location;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class VendorLocation
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $location_id
 * @property string                $vendor_id
 * @property Location              $location
 * @property Vendor                $vendor
 * @property Collection<Condition> $conditions
 */
class VendorLocation extends Pivot
{
    use UuidTrait;

    protected $table = 'trigger_vendors_location';

    protected $fillable = [
        'location_id',
        'vendor_id',
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
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return MorphMany
     */
    public function conditions(): MorphMany
    {
        return $this->morphMany(Condition::class, 'vendor');
    }
}
