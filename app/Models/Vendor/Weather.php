<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use App\Models\Trigger\VendorLocation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Weather
 *
 * @package App\Models\Vendor
 * @property string         $id
 * @property string         $vendor_location_id
 * @property string         $source
 * @property string         $type
 * @property string         $value
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property VendorLocation $vendorLocation
 */
class Weather extends Model
{
    const SOURCE_OPEN_WEATHER_MAP = 'open_weather_map';

    const TYPE_TEMPERATURE = 'temperature';
    const TYPE_WIND = 'wind';
    const TYPE_PRESSURE = 'pressure';
    const TYPE_HUMIDITY = 'humidity';
    const TYPE_CLOUDS = 'clouds';
    const TYPE_RAIN = 'rain';
    const TYPE_SNOW = 'snow';

    use UuidTrait;

    protected $table = 'vendor_weather';

    protected $fillable = [
        'vendor_location_id',
        'source',
        'type',
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
