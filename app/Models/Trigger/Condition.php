<?php

namespace App\Models\Trigger;

use App\Casts\Json;
use App\Models\Storage;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Condition
 *
 * @package App\Models\Trigger
 * @property string         $id
 * @property string         $group_id
 * @property string         $vendor_type_id
 * @property string         $vendor_location_id
 * @property object         $parameters
 * @property integer        $order_index
 * @property boolean        $is_enabled
 * @property boolean        $is_inverted
 * @property Carbon         $refreshed_at
 * @property Carbon         $changed_at
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property string|null    $current_value
 * @property Group          $group
 * @property VendorType     $vendorType
 * @property VendorLocation $vendorLocation
 * @property-read Storage|null $media
 * @method static \Illuminate\Database\Eloquent\Builder|Condition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Condition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Condition query()
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereIsInverted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereOrderIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereRefreshedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereVendorLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condition whereVendorTypeId($value)
 * @mixin \Eloquent
 */
class Condition extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'trigger_conditions';

    protected $fillable = [
        'group_id',
        'vendor_type_id',
        'vendor_location_id',
        'parameters',
        'order_index',
        'is_enabled',
        'is_inverted',
        'refreshed_at',
        'changed_at',
    ];

    protected $casts = [
        'parameters' => 'object',
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
    public function vendorType(): BelongsTo
    {
        return $this->belongsTo(VendorType::class, 'vendor_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendorLocation(): BelongsTo
    {
        return $this->belongsTo(VendorLocation::class, 'vendor_location_id');
    }

    /**
     * @return MorphOne
     */
    public function media(): MorphOne
    {
        return $this->morphOne(Storage::class, 'fileable')
            ->where('field', 'media');
    }

    /*
     * Accessors
     */

    /**
     * @return mixed
     */
    public function getCurrentValueAttribute()
    {
        $this->loadMissing([
            'vendorType.vendor',
        ]);

        $vendorInstance = app($this->vendorType->vendor->callback);

        return $vendorInstance->getCurrentValue($this);
    }
}
