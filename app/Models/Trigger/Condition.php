<?php

namespace App\Models\Trigger;

use App\Models\Traits\UuidTrait;
use App\Services\Vendor\Classes\BaseVendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Condition
 *
 * @package App\Models\Trigger
 * @property string         $id
 * @property string         $group_id
 * @property string         $vendor_id
 * @property string         $vendor_location_id
 * @property array          $parameters
 * @property integer        $order_index
 * @property boolean        $is_enabled
 * @property Carbon         $refreshed_at
 * @property Carbon         $changed_at
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property string|null    $current_value
 * @property Group          $group
 * @property Vendor         $vendor
 * @property VendorLocation $vendorLocation
 */
class Condition extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'trigger_conditions';

    protected $fillable = [
        'group_id',
        'vendor_id',
        'vendor_location_id',
        'parameters',
        'order_index',
        'is_enabled',
        'refreshed_at',
        'changed_at',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $dates = [
        'refreshed_at',
        'changed_at',
    ];

    protected $appends = [
        'current_value',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendorLocation(): BelongsTo
    {
        return $this->belongsTo(VendorLocation::class, 'vendor_location_id');
    }

    /*
     * Accessors
     */

    /**
     * @return mixed
     */
    public function getCurrentValueAttribute()
    {
        /** @var BaseVendor $vendorInstance */
        $vendorInstance = app($this->vendor->callback);

        return $vendorInstance->getCurrentValue($this);
    }
}
