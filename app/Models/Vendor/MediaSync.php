<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use App\Models\Trigger\VendorLocation;
use App\Models\Trigger\VendorType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MediaSync
 *
 * @package App\Models\Vendor
 * @property string         $id
 * @property string         $vendor_type_id
 * @property string         $vendor_location_id
 * @property string         $value
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property VendorType     $vendorType
 * @property VendorLocation $vendorLocation
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync whereVendorLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaSync whereVendorTypeId($value)
 * @mixin \Eloquent
 */
class MediaSync extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'vendor_media_sync';

    protected $fillable = [
        'vendor_type_id',
        'vendor_location_id',
        'value',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function vendorType()
    {
        return $this->belongsTo(VendorType::class, 'vendor_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendorLocation()
    {
        return $this->belongsTo(VendorLocation::class, 'vendor_location_id');
    }
}
