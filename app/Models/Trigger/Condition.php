<?php

namespace App\Models\Trigger;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Condition
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $group_id
 * @property string                $vendor_type
 * @property string                $vendor_id
 * @property array                 $parameters
 * @property integer               $order_index
 * @property boolean               $is_enabled
 * @property Carbon                $refreshed_at
 * @property Carbon                $changed_at
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property Group                 $group
 * @property Vendor|VendorLocation $vendor
 */
class Condition extends Model
{
    use UuidTrait;

    protected $table = 'trigger_conditions';

    protected $fillable = [
        'group_id',
        'vendor_type',
        'vendor_id',
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
     * @return MorphTo
     */
    public function vendor(): MorphTo
    {
        return $this->morphTo('vendor');
    }
}
