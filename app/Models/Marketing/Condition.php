<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class Condition
 *
 * @package App\Models\Marketing
 * @property string         $id
 * @property string         $group_id
 * @property string         $vendor_id
 * @property array          $parameters
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property Group          $group
 * @property VendorLocation $vendorLocation
 */
class Condition extends Pivot
{
    use UuidTrait;

    protected $table = 'marketing_conditions';

    protected $fillable = [
        'group_id',
        'vendor_location_id',
        'parameters',
    ];

    protected $casts = [
        'parameters' => 'array',
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
    public function vendorLocation(): BelongsTo
    {
        return $this->belongsTo(VendorLocation::class, 'vendor_location_id');
    }
}
