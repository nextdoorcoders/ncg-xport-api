<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Condition
 *
 * @package App\Models\Marketing
 * @property string $id
 * @property string $group_id
 * @property string $vendor_id
 * @property string $parameters
 * @property Group  $group
 * @property Vendor $vendor
 */
class Condition extends Model
{
    use UuidTrait;

    protected $table = 'marketing_conditions';

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
}
