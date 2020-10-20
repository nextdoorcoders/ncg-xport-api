<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use App\Models\Trigger\VendorLocation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Weather
 *
 * @package App\Models\Vendor
 * @property string         $id
 * @property string         $vendor_id
 * @property string         $vendor_location_id
 * @property string         $source
 * @property string         $value
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property VendorLocation $vendorLocation
 */
class Weather extends Model
{
    use HasFactory, UuidTrait;

    const SOURCE_OPEN_WEATHER_MAP = 'open_weather_map';

    protected $table = 'vendor_weather';

    protected $fillable = [
        'vendor_id',
        'vendor_location_id',
        'source',
        'value',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function vendorLocation()
    {
        return $this->belongsTo(VendorLocation::class, 'vendor_location_id');
    }
}
