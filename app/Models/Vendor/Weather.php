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
 * Class Weather
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
 * @method static \Illuminate\Database\Eloquent\Builder|Weather newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Weather newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Weather query()
 * @method static \Illuminate\Database\Eloquent\Builder|Weather whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weather whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weather whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weather whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weather whereVendorLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weather whereVendorTypeId($value)
 * @mixin \Eloquent
 */
class Weather extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'vendor_weather';

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
